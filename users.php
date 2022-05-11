<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    require_once "config/config.php";
    require_once "inc/auth_validate.php";


    $total_pages = 1;
    $limit = 15;
    $page = filter_input(INPUT_GET, 'page');

    if(!$page){
        $page = 1;
    }

    $offset = ($page - 1) * $limit;

    $query1 = "SELECT * FROM `users` LIMIT ".$limit." OFFSET ".$offset."";
    $execute1 = mysqli_query($conn, $query1);

    $query2 = "SELECT * FROM `users`";
    $execute2 = mysqli_query($conn, $query2);

    if(mysqli_num_rows($execute2) > 0){
        $total_users = mysqli_num_rows($execute2);

        $total_pages = ceil($total_users / $limit);
    }

    // echo '<pre>';print_r($execute);echo '</pre>';exit;
?>

<?php
    echo '<link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.5/dist/flowbite.min.css" />
    <script src="https://unpkg.com/flowbite@1.4.5/dist/flowbite.js"></script>';
    include "inc/head.php";

    include "inc/header.php";
?>


<main class="h-full pb-16 overflow-y-auto">
    <div class="container grid px-6 mx-auto">
        <div class="flex justify-between">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Users</h2>
            <a href="add_user.php" class="my-6 px-10 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none">Add User</a>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">S.No</th>
                            <th class="px-4 py-3">Username</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Profile Image</th>
                            <th class="px-4 py-3">Created Date</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <?php
                            if(mysqli_num_rows($execute1) > 0){
                                $i = 1;
                                while($user = mysqli_fetch_assoc($execute1)){
                                    $status = 'NA';
                                    $profile_image = 'uploads/users/default.png';
                                    if(file_exists($user['profile_image'])){
                                        $profile_image = $user['profile_image'];
                                    }

                                    if($user['is_active'] == 1){
                                        $status = 'Active';
                                    }else{
                                        $status = 'InActive';
                                    }


                        ?>
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3 text-sm"><?php echo $i;?></td>
                                        <td class="px-4 py-3 text-sm"><?php echo $user['name'];?></td>
                                        <td class="px-4 py-3 text-sm"><?php echo $user['email'];?></td>
                                        <td class="px-4 py-3 text-xs">
                                            <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                <?php echo $status;?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <img src="<?php echo $profile_image;?>" alt="Profile Image" width="60" height="50">
                                        </td>
                                        <td class="px-4 py-3 text-sm"><?php echo $user['created_at'];?></td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center space-x-4 text-sm">
                                                <!-- Button trigger modal -->
                                                <button class="open-modal flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" show-popup="view-modal-<?php echo $user['id'];?>">
                                                    <svg fill="currentColor" class="w-5 h-5" aria-hidden="true" viewBox="0 0 16 16">
                                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                    </svg>
                                                </button>
                                                <!-- <button class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit"> -->
                                                <a href="edit_user.php?user_id=<?php echo $user['id'];?>" title="Edit">
                                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                    </svg>
                                                </a>
                                                <!-- </button> -->
                                                <button class="open-modal flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"show-popup="delete-modal-<?php echo $user['id'];?>">
                                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                        <div id="view-modal-<?php echo $user['id'];?>" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center modal-open">
                                            <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">

                                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

                                                    <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                            Profile Details
                                                        </h3>
                                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white close-modal" data-modal-toggle="defaultModal">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                        </button>
                                                    </div>

                                                    <div class="">
                                                        <div class=" p-5">
                                                          <div class="p-3 rounded-xl hover:shadow">
                                                             <div class="flex w-full">
                                                                <img src="<?php echo $profile_image;?>" width="150" class="rounded-lg">
                                                                <div class="ml-6 mb-3">
                                                                   <div class="p-3">
                                                                      <h3 class="text-2xl text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:text-gray-200"><?php echo $user['name'];?></h3>
                                                                      <span class="text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:text-gray-400"><?php echo $user['email'];?></span><br>
                                                                      <span class="text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:text-gray-400"><?php echo $user['gender'];?></span><br>
                                                                      <span class="text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:text-gray-400"><?php echo $user['mobile_no'];?></span><br>
                                                                      <span class="text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:text-gray-400"><?php echo $user['address'];?></span>
                                                                   </div>

                                                                </div>
                                                             </div>
                                                          </div>
                                                       </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div id="delete-modal-<?php echo $user['id'];?>" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center modal-open">
                                            <div class="relative p-4 w-full max-w-md h-full md:h-auto">
                                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                    <button type="button" class="close-modal absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="popup-modal">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                    </button>
                                                    <div class="p-6 text-center">
                                                        <form method="post" action="delete_user.php">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id'];?>">
                                                            <svg class="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this user?</h3>
                                                            <button data-modal-toggle="popup-modal" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                                                Yes, I'm sure
                                                            </button>
                                                            <button data-modal-toggle="popup-modal" type="button" class="close-modal text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>


                        <?php
                                    $i++;
                                }
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
<?php include 'inc/footer.php';?>
<script>
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
</script>
