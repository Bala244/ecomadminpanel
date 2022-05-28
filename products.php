<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    require_once "config/config.php";
    require_once "inc/auth_validate.php";

    $search_str = filter_input(INPUT_GET, 'search_str');
    $category_id = filter_input(INPUT_GET, 'category_id');
    $sub_category_id_1 = filter_input(INPUT_GET, 'sub_category_id_1');
    $sub_category_id_2 = filter_input(INPUT_GET, 'sub_category_id_2');
    $sub_category_id_3 = filter_input(INPUT_GET, 'sub_category_id_3');
    $sub_category_id_4 = filter_input(INPUT_GET, 'sub_category_id_4');
    $sub_category_id_5 = filter_input(INPUT_GET, 'sub_category_id_5');
    $order_by_column = filter_input(INPUT_GET, 'order_by_column');
    $order_by_type = filter_input(INPUT_GET, 'order_by_type');

    $db = getDbInstance();
    if($category_id != ''){
        $db->where('category_id', $category_id);
    }
    if($sub_category_id_1 != ''){
        $db->where('sub_category_id_1', $sub_category_id_1);
    }
    if($sub_category_id_2 != ''){
        $db->where('sub_category_id_2', $sub_category_id_2);
    }
    if($sub_category_id_3 != ''){
        $db->where('sub_category_id_3', $sub_category_id_3);
    }
    if($sub_category_id_4sub_category_id_1 != ''){
        $db->where('sub_category_id_4', $sub_category_id_4);
    }
    if($sub_category_id_5 != ''){
        $db->where('sub_category_id_5', $sub_category_id_5);
    }
    if($search_str != ''){
        $db->where("name LIKE '%".$search_str."%' OR sku_code='".$search_str."'");
    }
    $products = $db->get('products');

    $main_categories = $db->get('category');
    include "inc/head.php";
    include "inc/header.php";
    echo '<link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.5/dist/flowbite.min.css" />
    <script src="https://unpkg.com/flowbite@1.4.5/dist/flowbite.js"></script>';
?>
<style>
    .bg-filter{
        background: #7e3af233;
        color: #7e3af2;
    }
    .filters{
        display: inline-block;
        position: relative;
        z-index: 10000;
    }
    .px-8{
        padding-left: 2rem;
        padding-right: 2rem;
    }
    .toggle-click-filter{
        cursor: pointer;
    }
</style>
<main class="h-full pb-16 overflow-y-auto">
    <div class="container grid px-6 mx-auto">
        <div class="flex justify-between">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Products</h2>
            <div class="my-6">

                <div class="filters">
                    <!-- <a href="javascript::" class="my-6 px-5 py-3 font-medium leading-5 transition-colors duration-150 bg-filter rounded-lg"  id="menu-button" aria-expanded="true" aria-haspopup="true"><i class="fa-solid fa-filter"></i></a> -->
                    <div class="menu-list origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                  </div>
                </div>
                <a href="uploads/products/upload_product_sample_file.csv" class="my-6 px-10 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none" download>Sample CSV File</a>
                <a href="add_product.php" class="my-6 px-10 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none">Add Product</a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg my-3">
            <div class="flex justify-between items-center toggle-click-filter hover:bg-gray-100 pt-4 pr-4 pl-4 pb-2 rounded-lg">
                <h2 class="px-3 text-xl mb-2 font-semibold text-gray-700 dark:text-gray-200">Filters</h2>
                <a href="javascript::" class="px-5 py-3 font-medium leading-5 transition-colors duration-150 rounded-lg" id="menu-button" aria-expanded="true" aria-haspopup="true"><i class="icon-class fa-solid fa-plus"></i></a>
            </div>
            <form class="toggle-click-filter-open mb-3 pb-4 pr-4 pl-4 flex justify-between flex-wrap px-3" method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" style="display: none;">
                <input class="p-4 block mt-1 mr-4 w-64 max-w-2xl text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="search_str" placeholder="Name OR SKU Code">
                <select name="category_id" class="sub_category_1 p-4 block mt-1 mr-4 w-64 max-w-2xl text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value="">Select Category</option>
                    <?php foreach ($main_categories as $main_category) { ?>
                      <option value="<?php echo $main_category['id'] ?>" <?php echo ( $product['category_id'] ==  $main_category['id'] ) ? 'selected' : '' ?> ><?php echo $main_category['name'] ?></option>
                    <?php } ?>
                </select>

                <select name="sub_category_id_1" class="sub_category_2 p-4 block mt-1 mr-4 w-64 max-w-2xl text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value="">Select Sub Category 1</option>
                </select>

                <select name="sub_category_id_2" class="sub_category_3 p-4 block mt-1 mr-4 w-64 max-w-2xl text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value="">Select Sub Category 2</option>
                </select>

                <select name="sub_category_id_3" class="sub_category_4 p-4 block mt-1 mr-4 w-64 max-w-2xl text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value="">Select Sub Category 3</option>
                </select>

                 <select name="sub_category_id_4" class="sub_category_5 p-4 block mt-1 mr-4 w-64 max-w-2xl text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value="">Select Sub Category 4</option>
                </select>

                <select name="sub_category_id_5" class="sub_category_6 p-4 block mt-1 mr-4 w-64 max-w-2xl text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value="">Select Sub Category 5</option>
                </select>

                <select name="order_by_column" class="p-4 block mt-1 mr-4 w-64 max-w-2xl text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value="">Select Order By Column</option>
                    <option value="quantity">Quantity</option>
                    <option value="amount">Amount</option>
                </select>

                <select name="order_by_type" class="p-4 block mt-1 mr-4 w-64 max-w-2xl text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value="">Select Order By Value</option>
                    <option value="asc">ASC</option>
                    <option value="desc">DESC</option>
                </select>

                <div class="flex justify-end flex-1 ">
                    <a href="javascript::" class=" mt-4 mr-2 px-4 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg hover:bg-gray-700 focus:outline-none clear-btn">Clear</a>
                    <!-- <a href="javascript::" class="my-3 px-8 py-2 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none">Search</a> -->
                    <button class="mt-4 mr-4 px-8 py-2 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none" aria-label="Submit">
                        Submit
                    </button>
                </div>
            </form>
        </div>

        <div class="w-full m-auto overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">S.No</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3 w-56">SKU Code</th>
                            <th class="px-4 py-3 w-56">Quantity</th>
                            <th class="px-4 py-3 w-56">Status</th>
                            <th class="px-4 py-3 w-2/12">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <?php
                        $i = 1;
                        foreach ($products as $product){
                            $status = 'NA';
                            if($product['status'] == 1){
                                $status = 'Active';
                            }else{
                                $status = 'InActive';
                            }
                    ?>
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3"><?php echo $i; ?></td>
                                <td class="px-4 py-3"><?php echo $product['name']; ?></a></td>
                                <td class="px-4 py-3"><?php echo $product['sku_code']; ?></td>
                                <td class="px-4 py-3"><?php echo $product['quantity']; ?></td>
                                <td class="px-4 py-3"><?php echo $status; ?></td>
                                <td class="px-4 py-3">
                                     <div class="flex items-center space-x-4 text-sm">
                                         <a href="product_detail.php?id=<?php echo $product['id'];?>" title="View">
                                             <svg fill="currentColor" class="w-5 h-5" aria-hidden="true" viewBox="0 0 16 16">
                                                 <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                 <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                             </svg>
                                         </a>
                                          <!-- <button class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit"> -->
                                          <a href="edit_product.php?id=<?php echo $product['id'];?>" title="Edit">
                                              <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                                  <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                              </svg>
                                          </a>
                                          <!-- </button> -->
                                          <button class="open-modal flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"show-popup="delete-modal-<?php echo $product['id'];?>">
                                              <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                                  <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                              </svg>
                                          </button>
                                      </div>
                                </td>
                            </tr>


                            <div id="delete-modal-<?php echo $product['id'];?>" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center modal-open">
                                <div class="relative p-4 w-full max-w-md h-full md:h-auto">
                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                        <button type="button" class="close-modal absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="popup-modal">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                        <div class="p-6 text-center">
                                            <form method="post" action="delete_product.php">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id'];?>">
                                                <svg class="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this Product?</h3>
                                                <button data-modal-toggle="popup-modal" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                                    Yes, I'm sure
                                                </button>
                                                <button data-modal-toggle="popup-modal" type="button" class="close-modal text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                            $i++;
                        }
                    ?>

                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Showing 21-30 of 100
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        <ul class="inline-flex items-center">
                            <li>
                                <button class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-purple" aria-label="Previous">
                                    <svg class="w-4 h-4 fill-current" aria-hidden="true" viewBox="0 0 20 20">
                                        <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" fill-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <button class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                    1
                                </button>
                            </li>
                            <li>
                                <button class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                    2
                                </button>
                            </li>
                            <li>
                                <button class="px-3 py-1 text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600 rounded-md focus:outline-none focus:shadow-outline-purple">
                                    3
                                </button>
                            </li>
                            <li>
                                <button class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                    4
                                </button>
                            </li>
                            <li>
                                <span class="px-3 py-1">...</span>
                            </li>
                            <li>
                                <button class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                    8
                                </button>
                            </li>
                            <li>
                                <button class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                    9
                                </button>
                            </li>
                            <li>
                                <button class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-purple" aria-label="Next">
                                    <svg class="w-4 h-4 fill-current" aria-hidden="true" viewBox="0 0 20 20">
                                        <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" fill-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </li>
                        </ul>
                    </nav>
                </span>
            </div>
        </div>
    </div>
    <div modal-backdrop="" class="modal-backdrop bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40 hidden"></div>
</main>

<?php include 'inc/footer-links.php';?>
<script>
    $(document).ready(function(){
        $('.toggle-click').off('click').click(function(){
            // e.stopPropagation();
            console.log('error', $(this).parent().parent().children('ul'));

            // $('.main-ul').addClass('custom-hidden');
            $(this).parent().parent().children('ul').toggle();

            if ($(this).text() == '+') {
                $(this).text('-');
            }else{
                $(this).text('+');
            }

            // console.log('here', $(this).text());
        });
    });
</script>
<?php include 'inc/footer.php';?>
<script>
    $(document).ready(function(){
        $('.close-modal').click(function(){
            $('.modal-open').addClass('hidden');
            $('.modal-backdrop').hide();
        });

        $('.open-modal').on('click', function(){
            var modalId = $(this).attr('show-popup');
            $('.modal-open').addClass('hidden');

            setTimeout(function(){
                console.log(modalId);
                $('#'+modalId).addClass('flex');
                $('#'+modalId).removeClass('hidden');
                $('.modal-backdrop').show();

            }, 10);
        });
    });
</script>
<script>
    // $('#menu-button').click(function(){
    //     $('.menu-list').toggle();
    //     $('.modal-backdrop').toggle();
    // });
    $('.clear-btn').click(function(){
        $('form')[0].reset();
    });

</script>
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
              // $('.sub_category_3, .sub_category_4').html('<option>Select Sub Category 1</option>');
            }

          });
        });

        $('.sub_category_2').change(function(){
          $.ajax({
            url: "ajax_data.php?id="+this.value+"&cate_id=sub_category_2",

            success: function(result){
              $('.sub_category_3').html('');
              $('.sub_category_3').append(result);
              // $('.sub_category_4').html('<option>Select Sub Category 2</option>');
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
    $(document).ready(function(){
        // $('.toggle-click-filter-open').hide();
        $('.toggle-click-filter').click(function(){
            $('.toggle-click-filter-open').toggle();
            $(this).find('i').toggleClass('fa-plus fa-minus');
        });
    });
</script>