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


    $db->where('product_id',$id);
    $product_images = $db->get('product_images', NULL, 'id,filepath');

    $db->where('id',$product['category_id']);
    $category = $db->getOne('category', NULL, 'name');
    $category_name = $category['name'];


    $sub_category_1_name = '';
    $sub_category_2_name = '';
    $sub_category_3_name = '';
    $sub_category_4_name = '';
    $sub_category_5_name = '';

    if ($product['sub_category_id_1'] != NULL) {
      $db->where('id',$product['sub_category_id_1']);
      $sub_category_1 = $db->getOne('sub_category_1', NULL, 'name');
      $sub_category_1_name = $sub_category_1['name'];
    }
    
    if ($product['sub_category_id_2'] != NULL) {
      $db->where('id',$product['sub_category_id_2']);
      $sub_category_2 = $db->getOne('sub_category_2', NULL, 'name');
      $sub_category_2_name = $sub_category_2['name'];
    }

    if ($product['sub_category_id_3'] != NULL) {
      $db->where('id',$product['sub_category_id_3']);
      $sub_category_3 = $db->getOne('sub_category_3', NULL, 'name');
      $sub_category_3_name = $sub_category_3['name'];
    }

    if ($product['sub_category_id_4'] != NULL) {
      $db->where('id',$product['sub_category_id_4']);
      $sub_category_4 = $db->getOne('sub_category_4', NULL, 'name');
      $sub_category_4_name = $sub_category_4['name'];
    }

    if ($product['sub_category_id_5'] != NULL) {
      $db->where('id',$product['sub_category_id_5']);
      $sub_category_5 = $db->getOne('sub_category_5', NULL, 'name');
      $sub_category_5_name = $sub_category_5['name'];
    }


    $breadArr = [$category_name, $sub_category_1_name, $sub_category_2_name, $sub_category_3_name, $sub_category_4_name, $sub_category_5_name];

    // echo '<pre>';print_r($breadArr);echo '</pre>';exit;


    include "inc/head.php";
    include "inc/header.php";
?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.1/dist/tailwind.min.css">
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,600;0,700;1,400&amp;display=swap">
<style>
  body { font-family: 'Rubik', sans-serif; }
[x-cloak] { display: none; }
.active{
  display: inline-block;background: #2eb9823b;padding: 1px 10px;border-radius: 10px;
}
.inactive{
  display: inline-block;background: #ea434021;padding: 1px 10px;border-radius: 10px;
}
</style>
<main class="h-full pb-16 overflow-y-auto">
  <input type="hidden" name="" class="product_id" value="<?php echo $id ?>">
  <div class="container grid px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
      View Product
    </h2>

    <div class="antialiased bg-white dark:bg-gray-800 shadow-lg rounded-lg">


      <div class="py-6">
        <!-- Breadcrumbs -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center space-x-2 text-gray-400 text-sm">
            
            <a href="products.php" class="hover:underline hover:text-gray-600">Product</a>
            <span class="leading-none-span">
              <svg class="h-5 w-5 leading-none text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </span>

          <a href="javascript::" class="" style="cursor: text;"><?php echo $product['name'] ?></a>
            <span class="leading-none-span">
              <svg class="h-5 w-5 leading-none text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </span>
          </div>
        </div>
        <!-- ./ Breadcrumbs -->

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
          <div class="flex flex-col md:flex-row -mx-4">
            <div class="md:flex-1 px-4">
              <div x-data="{ image: 1 }" x-cloak>
                <div class="h-64 md:h-80 rounded-lg bg-gray-100 mb-4">
                  <?php foreach ($product_images as $key => $value) { ?>
                  <div x-show="image === <?php echo $key+1; ?>" id="img-<?php echo $key+1 ?>" class="image-curo h-64 md:h-80 rounded-lg bg-gray-100 mb-4 flex items-center justify-center" style="background-image: url('<?php echo $value['filepath']; ?>');background-size: cover;">
                  </div>
                <?php } ?>
                </div>

                <div class="flex mb-4 ">
                    <div class=" flex items-center justify-center w-full">
                      <?php foreach ($product_images as $key => $value) { ?>
                        <div class="focus:outline-none w-full rounded-lg h-24 md:h-32 bg-gray-100 mr-3">
                            <img src="<?php echo $value['filepath']; ?>" data-id="img-<?php echo $key+1 ?>" class="img-curo-change w-full rounded-lg h-24 md:h-32" style="object-fit: cover; cursor: pointer;">
                        </div>
                      <?php } ?>
                    </div>
                </div>
              </div>
            </div>
            <div class="md:flex-1 px-4">
              <p class="text-sm <?php echo ($product['status'] == 1) ? 'text-green-500 active' : 'text-red-500 inactive' ?> font-semibold"><?php echo ($product['status'] == 1) ? 'Active' : 'InActive' ?></p>

              <h2 class="mb-2 leading-tight tracking-tight font-bold text-gray-800 text-2xl md:text-3xl dark:text-gray-100"><?php echo $product['name'] ?>.</h2>
              <p class="text-gray-800 text-sm font-bold">SKU: <?php echo $product['sku_code']; ?></p>
              <p class="mt-4 text-gray-500 text-sm font-bold <?php echo ($breadArr[0] != '') ? '' : 'hidden' ?>">Category: <?php echo $breadArr[0]; ?></p>
              <p class="text-gray-500 text-sm font-bold <?php echo ($breadArr[1] != '') ? '' : 'hidden' ?>">Sub Category 1: <?php echo $breadArr[1]; ?></p>
              <p class="text-gray-500 text-sm font-bold <?php echo ($breadArr[2] != '') ? '' : 'hidden' ?>">Sub Category 2: <?php echo $breadArr[2]; ?></p>
              <p class="text-gray-500 text-sm font-bold <?php echo ($breadArr[3] != '') ? '' : 'hidden' ?>">Sub Category 3: <?php echo $breadArr[3]; ?></p>
              <p class="text-gray-500 text-sm font-bold <?php echo ($breadArr[4] != '') ? '' : 'hidden' ?>">Sub Category 4: <?php echo $breadArr[4]; ?></p>
              <p class="mb-5 text-gray-500 text-sm font-bold <?php echo ($breadArr[5] != '') ? '' : 'hidden' ?>">Sub Category 5: <?php echo $breadArr[5]; ?></p>


              <div class="flex items-center justify-start w-full space-x-4 my-4">
                <div class="relative flex rounded-lg bg-green-50 flex">
                  <div class="px-3 text-green-400 text-left left-0 pt-2 right-0 absolute block text-xs uppercase text-gray-400 tracking-wide font-semibold">Retail</div>
                  <div class="cursor-pointer appearance-none  text-green-500 rounded-xl border border-gray-200 pl-8 pr-16 h-14 flex items-end pb-1">Rs. <?php echo ($product['is_retail'] == null) ? '0' : $product['retail_price'] ?></div>
                </div>
                <div class="relative flex  rounded-lg bg-purple-50 flex">
                  <div class="px-3 text-left text-purple-400 left-0 pt-2 right-0 absolute block text-xs uppercase text-gray-400 tracking-wide font-semibold">Whole Sale</div>
                  <div class="text-purple-500 cursor-pointer appearance-none rounded-xl border border-gray-200 pl-8 pr-16 h-14 flex items-end pb-1">Rs. <?php echo ($product[' is_whole_sale'] == null) ? '0' : $product['whole_sale_price'] ?></div>
                </div>
                <div class="relative  flex rounded-lg bg-yellow-50 flex">
                  <div class="px-3 text-yellow-400 text-left left-0 pt-2 right-0 absolute block text-xs uppercase text-gray-400 tracking-wide font-semibold">Ecommerce</div>
                  <div class="cursor-pointer text-yellow-500 appearance-none rounded-xl border border-gray-200 pl-8 pr-16 h-14 flex items-end pb-1">Rs. <?php echo ($product['is_ecommerce'] == null) ? '0' : $product['ecommerce_price'] ?></div>
                </div>
              </div>

              <p class="text-gray-500"><?php echo $product['description'] ?></p>

              <div class="flex py-4 space-x-4">

                 <div>
                  <div class="rounded-lg bg-gray-100 flex py-2 px-3">
                    <span class="text-indigo-400 mr-1 mt-1">QTY.</span>
                    <span class="font-bold text-indigo-600 text-3xl"><?php echo $product['quantity'] ?></span>
                  </div>
                </div>
              </div>
                
            </div>
          </div>
        </div>
      </div>
  </div>

  </div>
  </div>
</main>
<?php include 'inc/footer-links.php';?>

<?php include 'inc/footer.php';?>

<script>
  $(document).ready(function(){
    $('.img-curo-change').click(function(){
      var id = $(this).attr('data-id');
      console.log(id);
      $('.image-curo').hide();
      $('#'+id).show();
    });
    $('.leading-none-span:last').hide();
  });
</script>
