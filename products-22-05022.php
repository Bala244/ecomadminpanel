<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    require_once "config/config.php";
    require_once "inc/auth_validate.php";

    $db = getDbInstance();
    $products = $db->get('products');


    include "inc/head.php";
    include "inc/header.php";
        echo '<link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.5/dist/flowbite.min.css" />
    <script src="https://unpkg.com/flowbite@1.4.5/dist/flowbite.js"></script>';
?>

<main class="h-full pb-16 overflow-y-auto">
    <div class="container grid px-6 mx-auto">
        <div class="flex justify-between">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Products</h2>
            <a href="add_product.php" class="my-6 px-10 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none">Add Product</a>
        </div>

        <div class="w-full m-auto overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">S.No</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3 w-56">Description</th>
                            <th class="px-4 py-3 w-56">Quantity</th>
                            <th class="px-4 py-3 w-56">Sales Type</th>
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
                                <td class="px-4 py-3"><?php echo $product['description']; ?></td>
                                <td class="px-4 py-3"><?php echo $product['quantity']; ?></td>
                                <td class="px-4 py-3"><?php echo $product['sales_type']; ?></td>
                                <td class="px-4 py-3"><?php echo $status; ?></td>
                                <td class="px-4 py-3">
                                     <div class="flex items-center space-x-4 text-sm">
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
