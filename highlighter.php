<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    require_once "config/config.php";
    require_once "inc/auth_validate.php";
    $db = getDbInstance();
    $highlighters = $db->get('highlighter');

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        // echo '<pre>';print_r($_FILES);echo '</pre>';exit;

        if(isset($_FILES['highlighter_image']) && $_FILES['highlighter_image']['name'] != ''){
            $filename = basename($_FILES['highlighter_image']['name']);
            $temp_path = $_FILES['highlighter_image']['tmp_name'];
            $upload_folder = "uploads/highlighter/".$filename;

            if(move_uploaded_file($temp_path, $upload_folder)){
                $filepath = $upload_folder;

                $name = isset($_POST['name']) && $_POST['name'] != '' ? $_POST['name'] : '';
                $status = isset($_POST['status']) && $_POST['status'] != '' ? $_POST['status'] : '';
                $image = $filepath;
                $created_at = date('Y-m-d H:i:s');
                $created_by = $_SESSION['user_id'];
        
                $query1 = "INSERT INTO `highlighter` (`name`, `image`, `status`, `created_at`, `created_by`)
                    VALUES ('".$name."', '".$image."', '".$status."', '".$created_at."', '".$created_by."')";
                $execute1 = mysqli_query($conn, $query1);
        
                if($execute1){
                    $_SESSION['success'] = 'Image Created Successfully';
                    header("Location:highlighter.php");exit;
                }else{
                    $_SESSION['failure'] = 'Please Try Again.';
                    header("Location:highlighter.php");exit;
                }

            }else{
                $filepath = '';
                $_SESSION['failure'] = "Highter Image not Uploaded";
                header("Location:highlighter.php");exit;
            }
        }


    }


    include "inc/head.php";
    include "inc/header.php";

    echo '<link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.5/dist/flowbite.min.css" />
    <script src="https://unpkg.com/flowbite@1.4.5/dist/flowbite.js"></script>';
?>

<main class="h-full pb-16 overflow-y-auto">
<div id="toast-success" class="toast-msg fixed flex items-center p-4 mb-4 w-full max-w-xs text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert" style="top:6rem;right:4rem;display:none;"> 
    <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
        <span class="sr-only">Check icon</span>
    </div>
    <div class="ml-3 text-sm font-normal">Item Updated successfully.</div>
</div>
<div id="toast-danger" class="toast-msg fixed flex items-center p-4 mb-4 w-full max-w-xs text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert" style="top:6rem;right:4rem;display:none">
    <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        <span class="sr-only">Error icon</span>
    </div>
    <div class="ml-3 text-sm font-normal">Something Went Wrong.</div>
</div>
    <div class="container grid px-6 mx-auto">
        <div class="flex justify-between">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Highlighter</h2>
            <a href="javascript::" class="open-modal-add my-6 px-10 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none">Add Image</a>
        </div>

        <div class="w-full m-auto overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="datatable w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">S.No</th>
                            <th class="px-4 py-3">Image</th>
                            <th class="px-4 py-3 w-56">Name</th>
                            <th class="px-4 py-3 w-56">Created At</th>
                            <th class="px-4 py-3 w-2/12">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <?php
                            $i = 1;
                            foreach ($highlighters as $highlighter)
                            {
                                $status = '';
                                if($highlighter['status'] == 1){
                                    $status = 'Active';
                                }else{
                                    $status = 'In Active';
                                }
                                $date = $highlighter['created_at'];
                                $date = strtotime($date);
                                $date = date('d/M/Y', $date);
                        ?>

                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3"><?php echo $i; ?></td>
                                <td class="px-4 py-3"><a href="<?php echo $highlighter['image']; ?>" target="_blank"><img class="shadow" src="<?php echo $highlighter['image']; ?>" style="    width: 100px;height: auto;border-radius: 5px;"></a></td>
                                <td class="px-4 py-3"><?php echo $highlighter['name']; ?></td>
                                <td class="px-4 py-3"><?php echo $date; ?></td>
                                <td class="px-4 py-3" style="position:relative;">
                                    <div class="button r center" id="button-15">
                                        <input type="checkbox" class="checkbox status_checkbox" data-id="<?php echo $highlighter['id']; ?>"  <?php echo $highlighter['status'] == 1 ? 'checked' : '' ?>>
                                        <div class="knobs"></div>
                                        <div class="layer"></div>
                                    </div>
                                </td>
                            </tr>
                        <?php
                                $i++;
                            } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="add_modal" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center modal-open">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="close-modal absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="popup-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div class="p-6 text-center">
                <label class="block text-left text-gray-700 text-sm font-bold mb-2" for="username">
                    Please Upload an Highlighter Image
                </label>
                <form class="bg-white rounded px-8 pt-6 pb-8 mb-4" action="" method="post"  enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-left text-gray-700 text-sm font-bold mb-2" for="username">
                            Image
                        </label>
                        <input class="shadow appearance-none border rounded w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="file" name="highlighter_image" >
                    </div>
                    <div class="mb-4">
                        <label class="block text-left text-gray-700 text-sm font-bold mb-2" for="password">
                            Name
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" placeholder="name">
                    </div>
                    <div class="mb-4">
                        <label class="block text-left text-gray-700 text-sm font-bold mb-2" for="password">
                            Status
                        </label>
                        <select class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-end">
                    <button class="bg-blue-500 hover:bg-blue-700 mr-3 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                       Submit
                    </button>
                    <button data-modal-toggle="popup-modal" type="button" class="close-modal text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <div modal-backdrop="" class="modal-backdrop bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40 hidden"></div>

</main>

<?php include 'inc/footer-links.php';?>
<?php include 'inc/footer.php';?>

<script>
    $(document).ready(function(){

        $('.close-modal').click(function(){
            $('.modal-open').addClass('hidden');
            $('.modal-open-add').addClass('hidden');
            $('.modal-backdrop').hide();
        });

        $('.open-modal-add').on('click', function(){
            $('.modal-open-add').addClass('hidden');
            setTimeout(function(){
                $('#add_modal').addClass('flex');
                $('#add_modal').removeClass('hidden');
                $('.modal-backdrop').show();

            }, 10);
        });


        $('.status_checkbox').change(function(){
            if ($(this).is(":checked"))
            {
                var status = 1;
            }else{
                var status = 0;
            }
            
            var id = $(this).attr('data-id');
          $.ajax({
            url: "ajax_status_update.php?status="+status+"&id="+id+"",

            success: function(result){
                if(result == 'success'){
                    $('#toast-success').fadeIn().show();
                    setTimeout(() => {
                        $('#toast-success').fadeOut(500);
                    }, 2000);
                }else{
                    $('#toast-danger').fadeIn().show();
                    setTimeout(() => {
                        $('#toast-danger').fadeOut(500);
                    }, 2000);
                }
            }

          });
        });

    });
</script>
