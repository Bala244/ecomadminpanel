<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    date_default_timezone_set('Asia/Kolkata');
    $currdate = date('Y-m-d H:i:s');

    require_once "config/config.php";
    require_once "inc/auth_validate.php";

    $get_id = filter_input(INPUT_GET, 'id');
    $db = getDbInstance();
    $db->where('id', $get_id);
    $update_data = $db->getOne('category');

    // print_r($update_data);exit;



    if ($_POST) {

      // print_r($_POST);exit;
      $data['name'] = $_POST['name'];
      $data['description'] = $_POST['description'];
      $data['parent_id'] = 0;
      $data['status'] = $_POST['status'];
      $data['updated_at'] = $currdate;
      $data['updated_by'] = $_SESSION['user_id'];

      $db = getDbInstance();
      $db->where('id',$get_id);
      $resonce = $db->update('category',$data);
      header('location: categories.php');exit;
    }


    include "inc/head.php";
    include "inc/header.php";
?>



<main class="h-full pb-16 overflow-y-auto">
  <div class="container grid px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
      Edit Category
    </h2>
    <form action="" method="post">
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Name</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="name" placeholder="Jane Doe" value="<?php echo $update_data['name'] ?>" required>
          <input type="hidden" name="parent_id" class="parent_id" value="<?php echo $update_data['parent_id'] ?>">
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Description</span>
          <textarea class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="Enter some long form content." name="description"><?php echo $update_data['description'] ?></textarea>
        </label>


        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Status
          </span>
          <select name="status" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
            <option value="1" <?php echo ( $update_data['status'] == '1' ) ? 'selected' : '' ?>>Active</option>
            <option value="0" <?php echo ( $update_data['status'] == '0' ) ? 'selected' : '' ?>>Inactive</option>

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

        var par_id = $('.parent_id').val();

        $('.highlighter-none[data-id="'+par_id+'"]').addClass('text-zinc-50 px-3 bg-purple-600');
         $('.highlighter-none[data-id="'+par_id+'"]').parents(".custom-hidden").removeClass( "custom-hidden" );

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
          $('.highlighter-none').removeClass('text-zinc-50 px-3 bg-purple-600 custom-active-highlighter');
          $(this).addClass('text-zinc-50 px-3 bg-purple-600');
          var catId = $(this).attr('data-id');
          // console.log(catId);
          $('.parent_id').val(catId);
        });

        $('.reset-cat').off('click').click(function(){
          $('.highlighter-none').removeClass('text-zinc-50 px-3 bg-purple-600 custom-active-highlighter');
          $('.parent_id').val(0);

        });
    });
</script>
<?php include 'inc/footer.php';?>
