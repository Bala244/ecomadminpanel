<?php
<<<<<<< HEAD
=======
    ini_set("display_errors","1");
    error_reporting(E_ALL);

>>>>>>> f4b4fd0dea83f3f17bbb225081cb827d2bdd684a
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

        $file_error = 0;

        $data['name'] = $_POST['name'];
        $data['description'] = $_POST['description'];
        $data['category_id'] = $_POST['category_id'];
        $data['sub_category_id_1'] = $_POST['sub_category_id_1'];
        $data['sub_category_id_2'] = $_POST['sub_category_id_2'];
        $data['sub_category_id_3'] = $_POST['sub_category_id_3'];
        $data['quantity'] = $_POST['quantity'];
        $data['sales_type'] = $_POST['sales_type'];
        $data['price'] = $_POST['price'];
        $data['unique_code'] = $_POST['unique_code'];
        $data['status'] = $_POST['status'];
        $data['created_at'] = $created_at;
        $data['created_by'] = $_SESSION['user_id'];

<<<<<<< HEAD
        $db = getDbInstance();
        $last_id = $db->insert('products',$data);
        if($last_id){

            if(isset($_FILES['product_images']) && $_FILES['product_images']['name'][0] != ''){
                for($i=0;$i<count($_FILES['product_images']['name']);$i++){
                    $filename = $_FILES['product_images']['name'][$i];
                    $filepath = 'uploads/products/'.$filename;

                    if(move_uploaded_file($_FILES['product_images']['tmp_name'][$i], $filepath)){
=======
        $db = getDbInstance();        
        $last_id = $db->insert('products',$data);
        // echo '<pre>';print_r($db->getLastError());echo '</pre>';exit;



        if($last_id){
        // echo '<pre> bnk';print_r($_FILES['images']['name'][0]);echo '</pre>';exit;

            if(isset($_FILES['images']) && $_FILES['images']['name'][0] != ''){
                for($i=0;$i<count($_FILES['images']['name']);$i++){
                    $filename = $_FILES['images']['name'][$i];
                    $filepath = 'uploads/products/'.$filename;

                    if(move_uploaded_file($_FILES['images']['tmp_name'][$i], $filepath)){
>>>>>>> f4b4fd0dea83f3f17bbb225081cb827d2bdd684a
                        $data_to_db = array();
                        $data_to_db['product_id'] = $last_id;
                        $data_to_db['filepath'] = $filepath;
                        $data_to_db['created_at'] = $created_at;
                        $data_to_db['created_by'] = $_SESSION['user_id'];

                        $db =  getDbInstance();
<<<<<<< HEAD
                        $insert_id = $db->insert('product_images', $data_to_db);
=======
                        // echo '<pre>';print_r($data_to_db);echo '</pre>';exit;

                        $insert_id = $db->insert('product_images', $data_to_db);


>>>>>>> f4b4fd0dea83f3f17bbb225081cb827d2bdd684a
                    }else{
                        $file_error++;
                    }
                }
            }

            if($file_error > 0){
                $_SESSION['failure'] = 'Product images not uploaded completely';
            }

            $_SESSION['success'] = 'Product Created Successfully';
            header("Location:products.php");exit;
        }else{
            $_SESSION['failure'] = 'Product Not Created. Please Try Again.';
            header("Location:products.php");exit;
        }
    }


    include "inc/head.php";
    include "inc/header.php";
?>



<main class="h-full pb-16 overflow-y-auto">
  <div class="container grid px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
      Add Category
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
            <option>Choose a Value</option>
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
            <option>Choose a Value</option>
          </select>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Sub Category 2
          </span>
          <select name="sub_category_id_2" class="sub_category_3 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
            <option>Choose a Value</option>
          </select>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Sub Category 3
          </span>
          <select name="sub_category_id_3" class="sub_category_4 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option>Choose a Value</option>
          </select>
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Quantity</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="quantity" placeholder="Quantity" required>
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Sales Type</span>
          <select name="sales_type" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
            <option>Choose a Value</option>
            <option value="retail">Retail</option>
            <option value="whole_sale">Whole Sale</option>
          </select>
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Price</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="price" placeholder="Price" required>
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Unique Code</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="unique_code" placeholder="Unique Code / SK Code / QR Code">
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Product Images</span>
<<<<<<< HEAD
          <input type="file" name="product_images[]" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" autocomplete="Off" multiple>
=======
         <!--  <input type="file" name="images[]" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" autocomplete="Off" multiple> -->
          <div class="input-images"></div>

>>>>>>> f4b4fd0dea83f3f17bbb225081cb827d2bdd684a
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Status
          </span>
          <select name="status" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
            <option>Choose a Value</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </label>

        <div class="flex mt-6 mb-6 justify-end">
            <div>
              <button class="mr-4 px-10 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-zinc-600 border border-transparent rounded-lg hover:bg-zinc-800 focus:outline-none" onclick="window.location.href='categories.php'">
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
    });
</script>
<<<<<<< HEAD
=======
<script>
  $('.input-images').imageUploader({
    imagesInputName: 'images',
    preloadedInputName: 'preloaded',
    label: 'Drag & Drop files here or click to browse'
  });
</script>
>>>>>>> f4b4fd0dea83f3f17bbb225081cb827d2bdd684a
<?php include 'inc/footer.php';?>
