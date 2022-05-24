<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    date_default_timezone_set('Asia/Kolkata');
    $created_at = date('Y-m-d H:i:s');

    require_once "config/config.php";
    require_once "inc/auth_validate.php";


    $id = filter_input(INPUT_GET, 'id');

    $db = getDbInstance();

    $db->where('id',$id);
    $product = $db->getOne('products');

    $db = getDbInstance();
    $db->where('product_id',$id);
    $product_images = $db->get('product_images', NULL, 'id');

    $db = getDbInstance();
    $main_categories = $db->get('category');

    $db = getDbInstance();
    // $db->where('category_id', $product['category_id']);
    $sub_1_categories = $db->get('sub_category_1');

    $db = getDbInstance();
    // $db->where('sub_category_id_1', $product['sub_category_id_1']);
    $sub_2_categories = $db->get('sub_category_2');

    $db = getDbInstance();
    // $db->where('sub_category_id_2', $product['sub_category_id_2']);
    $sub_3_categories = $db->get('sub_category_3');

    $db = getDbInstance();
    // $db->where('sub_category_id_3', $product['sub_category_id_3']);
    $sub_4_categories = $db->get('sub_category_4');

    $db = getDbInstance();
    // $db->where('sub_category_id_4', $product['sub_category_id_4']);
    $sub_5_categories = $db->get('sub_category_5');


     if ($_POST) {

        // echo '<pre>';print_r($_FILES);echo '</pre>';exit;

        $file_error = 0;

        $data['name'] = $_POST['name'];
        $data['description'] = $_POST['description'];
        $data['category_id'] = $_POST['category_id'];
        $data['sub_category_id_1'] = $_POST['sub_category_id_1'];
        $data['sub_category_id_2'] = $_POST['sub_category_id_2'];
        $data['sub_category_id_3'] = $_POST['sub_category_id_3'];
        $data['sub_category_id_4'] = $_POST['sub_category_id_4'];
        $data['sub_category_id_5'] = $_POST['sub_category_id_5'];
        $data['quantity'] = $_POST['quantity'];
        $data['is_retail'] = 0;
        $data['is_whole_sale'] = 0;
        $data['is_ecommerce'] = 0;
        if($_POST['is_retail'] == 'on'){
            $data['is_retail'] = 1;
        }
        if($_POST['is_whole_sale'] == 'on'){
            $data['is_whole_sale'] = 1;
        }
        if($_POST['is_ecommerce'] == 'on'){
            $data['is_ecommerce'] = 1;
        }
        $data['retail_price'] = $_POST['retail_price'];
        $data['whole_sale_price'] = $_POST['whole_sale_price'];
        $data['ecommerce_price'] = $_POST['ecommerce_price'];
        $data['sku_code'] = $_POST['sku_code'];
        $data['status'] = $_POST['status'];
        $data['updated_at'] = $created_at;
        $data['updated_by'] = $_SESSION['user_id'];

        $db = getDbInstance();
        $db->where('id', $id);
        $resonce = $db->update('products',$data);
        if($resonce){

          $deleted_images = '';

          if (isset($_POST['old'])) {
            $deleted_images_ids = array_diff(array_column($product_images, 'id'), $_POST['old']);
            $deleted_images = implode(',', $deleted_images_ids);
          }

          $delete_query = "DELETE FROM product_images WHERE  id in (".$deleted_images.") ";

          mysqli_query($conn, $delete_query);

            if(isset($_FILES['images']) && $_FILES['images']['name'][0] != ''){
                for($i=0;$i<count($_FILES['images']['name']);$i++){
                    $filename = $_FILES['images']['name'][$i];
                    $upload_path = 'uploads/products/'.$filename;
                    $filepath = 'http://packurs.com/admin/uploads/products/'.$filename;

                    if(move_uploaded_file($_FILES['images']['tmp_name'][$i], $upload_path)){
                        $data_to_db = array();
                        $data_to_db['product_id'] = $id;
                        $data_to_db['filepath'] = $filepath;
                        $data_to_db['created_at'] = $created_at;
                        $data_to_db['created_by'] = $_SESSION['user_id'];

                        $db =  getDbInstance();
                        // echo '<pre>';print_r($data_to_db);echo '</pre>';exit;

                        $insert_id = $db->insert('product_images', $data_to_db);


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
  <input type="hidden" name="" class="product_id" value="<?php echo $id ?>">
  <div class="container grid px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
      Edit Product
    </h2>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Name</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="name" placeholder="Name" value="<?php echo $product['name']; ?>" required>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Description</span>
          <textarea class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="Product Description" name="description"><?php echo $product['description']; ?></textarea>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Category
          </span>
          <select name="category_id" class="sub_category_1 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
            <option>Choose a Value</option>
            <?php foreach ($main_categories as $main_category) { ?>
              <option value="<?php echo $main_category['id'] ?>" <?php echo ( $product['category_id'] ==  $main_category['id'] ) ? 'selected' : '' ?> ><?php echo $main_category['name'] ?></option>
            <?php } ?>

          </select>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Sub Category 1
          </span>
          <select name="sub_category_id_1" class="sub_category_2 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
            <option>Choose a Value</option>
            <?php foreach ($sub_1_categories as $sub_1_category) { ?>
              <option value="<?php echo $sub_1_category['id'] ?>" <?php echo ( $sub_1_category['id'] == $product['sub_category_id_1'] ) ? 'selected' : '' ?>><?php echo $sub_1_category['name'] ?></option>
            <?php } ?>

          </select>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Sub Category 2
          </span>
          <select name="sub_category_id_2" class="sub_category_3 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
            <option>Choose a Value</option>
            <?php foreach ($sub_2_categories as $sub_2_category) { ?>
              <option value="<?php echo $sub_2_category['id'] ?>" <?php echo ( $sub_2_category['id'] == $product['sub_category_id_2'] ) ? 'selected' : '' ?>><?php echo $sub_2_category['name'] ?></option>
            <?php } ?>

          </select>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Sub Category 3
          </span>
          <select name="sub_category_id_3" class="sub_category_4 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option>Choose a Value</option>
             <?php foreach ($sub_3_categories as $sub_3_category) {  ?>
              <option value="<?php echo $sub_3_category['id'] ?>" <?php echo ( $sub_3_category['id'] == $product['sub_category_id_3'] ) ? 'selected' : '' ?>><?php echo $sub_3_category['name'] ?></option>
            <?php } ?>
          </select>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Sub Category 4
          </span>
          <select name="sub_category_id_3" class="sub_category_4 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option>Choose a Value</option>
             <?php foreach ($sub_4_categories as $sub_4_category) {  ?>
              <option value="<?php echo $sub_4_category['id'] ?>" <?php echo ( $sub_4_category['id'] == $product['sub_category_id_4'] ) ? 'selected' : '' ?>><?php echo $sub_4_category['name'] ?></option>
            <?php } ?>
          </select>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Sub Category 5
          </span>
          <select name="sub_category_id_3" class="sub_category_4 block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option>Choose a Value</option>
             <?php foreach ($sub_5_categories as $sub_5_category) {  ?>
              <option value="<?php echo $sub_5_category['id'] ?>" <?php echo ( $sub_5_category['id'] == $product['sub_category_id_5'] ) ? 'selected' : '' ?>><?php echo $sub_5_category['name'] ?></option>
            <?php } ?>
          </select>
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Quantity</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="quantity" placeholder="Quantity" value="<?php echo $product['quantity']; ?>" required>
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Sales Type</span>
          <select name="sales_type" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
            <option>Choose a Value</option>
            <option value="retail" <?php echo ( $product['sales_type'] ==  'retail' ) ? 'selected' : '' ?>>Retail</option>
            <option value="whole_sale"<?php echo ( $product['sales_type'] ==  'whole_sale' ) ? 'selected' : '' ?>>Whole Sale</option>
          </select>
        </label>

        <label class="block text-sm">
            <input type="checkbox" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="is_retail">
            <span class="text-gray-700 dark:text-gray-400">Retail</span>

            <input type="checkbox" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="is_whole_sale">
            <span class="text-gray-700 dark:text-gray-400">Whole Sale</span>

            <input type="checkbox" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="is_ecommerce">
            <span class="text-gray-700 dark:text-gray-400">Ecommerce</span>
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Retail Price</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="retail_price" value="<?php echo $product['retail_price']; ?>" placeholder="Retail Price">
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Whole Sale Price</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="whole_sale_price" value="<?php echo $product['whole_sale_price']; ?>" placeholder="Whole Sale Price">
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Ecommerce Price</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="ecommerce_price" value="<?php echo $product['ecommerce_price']; ?>" placeholder="Ecommerce Price">
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">SKU Code</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="sku_code" placeholder="SKU Code" value="<?php echo $product['sku_code']; ?>">
        </label>

        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Product Images</span>
          <div class="input-images"></div>

        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Status
          </span>
          <select name="status" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
            <option>Choose a Value</option>
            <option value="1" <?php echo ( $product['status'] ==  '1' ) ? 'selected' : '' ?>>Active</option>
            <option value="0" <?php echo ( $product['status'] ==  '0' ) ? 'selected' : '' ?>>Inactive</option>
          </select>
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
              $('.sub_category_3, .sub_category_4').html('<option>Choose a Value</option>');
            }

          });
        });

        $('.sub_category_2').change(function(){
          $.ajax({
            url: "ajax_data.php?id="+this.value+"&cate_id=sub_category_2",

            success: function(result){
              $('.sub_category_3').html('');
              $('.sub_category_3').append(result);
              $('.sub_category_4').html('<option>Choose a Value</option>');
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
  var product_id = $('.product_id').val();
  var images = [];

  $.ajax({
    url: "ajax_images.php?id="+product_id,

    success: function(result){
      var data = $.parseJSON(result);
      $(data).each(function(i,v){
        // console.log(i+1, v.src);
        images.push({'id': v.id,'src': v.src});
      });
    }
  });

  let preloaded = images;

  setTimeout(function(){
      $('.input-images').imageUploader({
      preloaded: preloaded,
      imagesInputName: 'images',
      preloadedInputName: 'old',
    });
    },200);

</script>
<?php include 'inc/footer.php';?>
