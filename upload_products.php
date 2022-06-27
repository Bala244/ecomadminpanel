<?php
    ini_set("display_errors","1");
    error_reporting(E_ALL);

    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    date_default_timezone_set('Asia/Kolkata');
    $created_at = date('Y-m-d H:i:s');

    require_once "config/config.php";
    require_once "inc/auth_validate.php";


    $db = getDbInstance();
    $main_categories = $db->get('category');

    if ($_POST) {
        // echo '<pre>';print_r($_FILES);echo '</pre>';exit;

        $name = $_POST['name'];
        $description = $_POST['description'];
        $category_id = isset($_POST['category_id']) && $_POST['category_id'] != '' ? $_POST['category_id'] : NULL;
        $sub_category_id_1 = isset($_POST['sub_category_id_1']) && $_POST['sub_category_id_1'] != '' ? $_POST['sub_category_id_1'] : NULL;
        $sub_category_id_2 = isset($_POST['sub_category_id_2']) && $_POST['sub_category_id_2'] != '' ? $_POST['sub_category_id_2'] : NULL;
        $sub_category_id_3 = isset($_POST['sub_category_id_3']) && $_POST['sub_category_id_3'] != '' ? $_POST['sub_category_id_3'] : NULL;
        $sub_category_id_4 = isset($_POST['sub_category_id_4']) && $_POST['sub_category_id_4'] != '' ? $_POST['sub_category_id_4'] : NULL;
        $sub_category_id_5 = isset($_POST['sub_category_id_5']) && $_POST['sub_category_id_5'] != '' ? $_POST['sub_category_id_5'] : NULL;
        $retail_price = isset($_POST['retail_price']) && $_POST['retail_price'] != '' ? $_POST['retail_price'] : 0;
        $whole_sale_price = isset($_POST['whole_sale_price']) && $_POST['whole_sale_price'] != '' ? $_POST['whole_sale_price'] : 0;
        $ecommerce_price = isset($_POST['ecommerce_price']) && $_POST['ecommerce_price'] != '' ? $_POST['ecommerce_price'] : 0;

        if ($_FILES["file"]["size"] > 0){

            $filename = $_FILES['file']['name'];
            $filetmpname = $_FILES['file']['tmp_name'];

            if (($handle = fopen($filetmpname, "r")) !== FALSE){
                $row = 1;
                $i = 0;
                $failed_count = 0;
                $success_count = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE){
                    $num = count($data);
                    if($row > 1){
                        // echo '<pre>';print_r($data);echo '</pre>';exit;
                        $file_error = 0;

                        $data_to_insert = array();
                        $data_to_insert['name'] = $name;
                        $data_to_insert['description'] = $description;
                        $data_to_insert['category_id'] = $category_id;
                        $data_to_insert['sub_category_id_1'] = $sub_category_id_1;
                        $data_to_insert['sub_category_id_2'] = $sub_category_id_2;
                        $data_to_insert['sub_category_id_3'] = $sub_category_id_3;
                        $data_to_insert['sub_category_id_4'] = $sub_category_id_4;
                        $data_to_insert['sub_category_id_5'] = $sub_category_id_5;
                        $data_to_insert['retail_price'] = $retail_price;
                        $data_to_insert['whole_sale_price'] = $whole_sale_price;
                        $data_to_insert['ecommerce_price'] = $ecommerce_price;

                        $data_to_insert['sku_code'] = $data[0];
                        $data_to_insert['quantity'] = $data[1];
                        $data_to_insert['is_retail'] = 0;
                        $data_to_insert['is_whole_sale'] = 0;
                        $data_to_insert['is_ecommerce'] = 0;
                        if($data[2] == 'retail'){
                            $data_to_insert['is_retail'] = 1;
                        }
                        if($data[3] == 'whole sale'){
                            $data_to_insert['is_whole_sale'] = 1;
                        }
                        if($data[4] == 'ecommerce'){
                            $data_to_insert['is_ecommerce'] = 1;
                        }
                        $data_to_insert['status'] = 1;
                        $data_to_insert['created_at'] = $created_at;
                        $data_to_insert['created_by'] = $_SESSION['user_id'];

                        $response = checkskucode($data_to_insert['sku_code']);

                        if($response == 'not_exists'){

                            $db = getDbInstance();
                            $last_id = $db->insert('products',$data_to_insert);

                            if($last_id){
                                $product_images = explode(',', $data[5]);
                                if(isset($data[5]) && $data[5] != ''){
                                    for($i=0;$i<count($product_images);$i++){
                                        $filepath = trim($product_images[$i]);

                                        $data_to_db = array();
                                        $data_to_db['product_id'] = $last_id;
                                        $data_to_db['filepath'] = $filepath;
                                        $data_to_db['created_at'] = $created_at;
                                        $data_to_db['created_by'] = $_SESSION['user_id'];

                                        $db =  getDbInstance();
                                        // echo '<pre>';print_r($data_to_db);echo '</pre>';exit;

                                        $insert_id = $db->insert('product_images', $data_to_db);
                                    }
                                }
                                $success_count++;
                            }
                        }else{
                            $failed_count++;
                        }
                        // echo $success_count.' / '.$failed_count;exit;
                    }
                    $row++;
                }
            }
        }

        $total_product_count = $row - 2;

        if($success_count > 0){
            // $_SESSION['success'] = 'Product Uploaded Successfully. Totally '.$success_count.' products uploaded.';
            $_SESSION['success'] = $success_count." out of ".$total_product_count." products uploaded Successfully.";
        }else{
            $_SESSION['failure'] = $failed_count.' products failed to upload.';
        }
        header("Location:products.php");exit;

    }


    include "inc/head.php";
    include "inc/header.php";
?>



<main class="h-full pb-16 overflow-y-auto">
  <div class="container grid px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
      Upload Products
    </h2>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Name</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="name" placeholder="Name" required>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Description</span>
          <textarea class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="Product Description" name="description"></textarea>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Category
          </span>
          <select name="category_id" class="sub_category_1 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
            <option value="">Select Category</option>
            <?php foreach ($main_categories as $main_category) { ?>
              <option value="<?php echo $main_category['id'] ?>"><?php echo $main_category['name'] ?></option>
            <?php } ?>

          </select>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Sub Category 1
          </span>
          <select name="sub_category_id_1" class="sub_category_2 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
            <option value="">Select Sub Category 1</option>
          </select>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Sub Category 2
          </span>
          <select name="sub_category_id_2" class="sub_category_3 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
            <option value="">Select Sub Category 2</option>
          </select>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Sub Category 3
          </span>
          <select name="sub_category_id_3" class="sub_category_4 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="">Select Sub Category 3</option>
          </select>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Sub Category 4
          </span>
          <select name="sub_category_id_4" class="sub_category_5 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="">Select Sub Category 4</option>
          </select>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Sub Category 5
          </span>
          <select name="sub_category_id_5" class="sub_category_6 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="">Select Sub Category 5</option>
          </select>
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Retail Price</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="retail_price" placeholder="Retail Price">
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Whole Sale Price</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="whole_sale_price" placeholder="Whole Sale Price">
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Ecommerce Price</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="ecommerce_price" placeholder="Ecommerce Price">
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Upload CSV File</span>
          <input type="file" name="file" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" autocomplete="Off">
        </label>

        <div class="flex mt-6 mb-6 justify-end">
            <div>
              <button class="mr-4 px-10 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-zinc-600 border border-transparent rounded-lg hover:bg-zinc-800 focus:outline-none" onclick="window.location.href='products.php'">
                Cancel
              </button>
            </div>

            <div>
              <button type="submit" class=" px-10 py-3  font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none">
                Submit
              </button>
            </div>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include 'inc/footer-links.php';?>
<script>
    $(document).ready(function(){
        $('.toggle-click').off('click').click(function(){
            // $('.main-ul').addClass('custom-hidden');
            $(this).parent().parent().children('ul').toggle();

            if ($(this).text() == '+') {
                $(this).text('-');
            }else{
                $(this).text('+');
            }

            // console.log('here', $(this).text());
        });

        $('.highlighter-none').off('click').click(function(){
          $('.highlighter-none').removeClass('text-zinc-50 px-3 bg-purple-600');
          $(this).addClass('text-zinc-50 px-3 bg-purple-600');
          var catId = $(this).attr('data-id');
          console.log(catId);
          $('.parent_id').val(catId);
        });

        $('.reset-cat').off('click').click(function(){
          $('.highlighter-none').removeClass('text-zinc-50 px-3 bg-purple-600 custom-active-highlighter');
          $('.parent_id').val(0);

        });

        $('.sub_category_1').change(function(){
          $.ajax({
            url: "ajax_data.php?id="+this.value+"&cate_id=sub_category_1",

            success: function(result){
              $('.sub_category_2').html('');
              $('.sub_category_2').append(result);
            }

          });
        });

        $('.sub_category_2').change(function(){
          $.ajax({
            url: "ajax_data.php?id="+this.value+"&cate_id=sub_category_2",

            success: function(result){
              $('.sub_category_3').html('');
              $('.sub_category_3').append(result);
            }

          });
        });

        $('.sub_category_3').change(function(){
          $.ajax({
            url: "ajax_data.php?id="+this.value+"&cate_id=sub_category_3",

            success: function(result){
              $('.sub_category_4').html('');
              $('.sub_category_4').append(result);
            }

          });
        });

        $('.sub_category_4').change(function(){
          $.ajax({
            url: "ajax_data.php?id="+this.value+"&cate_id=sub_category_4",

            success: function(result){
              $('.sub_category_5').html('');
              $('.sub_category_5').append(result);
            }

          });
        });

        $('.sub_category_5').change(function(){
          $.ajax({
            url: "ajax_data.php?id="+this.value+"&cate_id=sub_category_5",

            success: function(result){
              $('.sub_category_6').html('');
              $('.sub_category_6').append(result);
            }

          });
        });
    });
</script>
<script>
  $('.input-images').imageUploader({
    imagesInputName: 'images',
    preloadedInputName: 'preloaded',
    label: 'Drag & Drop files here or click to browse'
  });
</script>
<?php include 'inc/footer.php';?>
