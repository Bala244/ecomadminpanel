<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    date_default_timezone_set('Asia/Kolkata');
    $currdate = date('Y-m-d H:i:s');

    require_once "config/config.php";
    require_once "inc/auth_validate.php";
    $result = mysqli_query($conn, "SELECT * FROM category WHERE status=1 ORDER BY parent_id");


    $category = array(
        'categories' => array(),
        'parent_cats' => array()
    );


    while ($row = mysqli_fetch_assoc($result)) {

        $category['categories'][$row['id']] = $row;

        $category['parent_cats'][$row['parent_id']][] = $row['id'];
    }

    function buildCategory($parent, $category, $hiddenClass) {
        $html = "";
        if (isset($category['parent_cats'][$parent])) {
            $html .= "<ul class='".$hiddenClass."'>\n";
            foreach ($category['parent_cats'][$parent] as $cat_id) {
                if (!isset($category['parent_cats'][$cat_id])) {
                    $html .= "<li class=''><div class='flex py-1 relative px-4'><span class='text-sm highlighter-none cursor-pointer' data-id=".$category['categories'][$cat_id]['id'].">" . $category['categories'][$cat_id]['name'] . "</span></div></li> \n";
                }
                if (isset($category['parent_cats'][$cat_id])) {
                    $html .= "<li class=''><div class='flex py-1 relative px-4'><span class='custom-plus toggle-click' style='position: absolute;left: -14px;'>+</span><span class='text-sm highlighter-none cursor-pointer' data-id=".$category['categories'][$cat_id]['id'].">" . $category['categories'][$cat_id]['name'] . "</span></div> \n";
                    $html .= buildCategory($cat_id, $category, 'main-ul custom-hidden');
                    $html .= "</li> \n";
                }
            }
            $html .= "</ul> \n";
        }
        return $html;
    }


    if ($_POST) {

        // print_r($_POST);exit;
        $data['name'] = $_POST['name'];
        $data['description'] = $_POST['description'];
        $data['parent_id'] = 0;
        $data['status'] = $_POST['status'];
        $data['created_at'] = $currdate;
        $data['created_by'] = $_SESSION['user_id'];

        $db = getDbInstance();

        $resonce = $db->insert('category',$data);
        header('location: categories.php');exit;
    }


    include "inc/head.php";
    include "inc/header.php";
?>



<main class="h-full pb-16 overflow-y-auto">
  <div class="container grid px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
      Add Category
    </h2>
    <form action="" method="post">
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Name</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="name" placeholder="Jane Doe" required>
          <input type="hidden" name="parent_id" class="parent_id" value="0">
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Description</span>
          <textarea class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="Enter some long form content." name="description"></textarea>
        </label>

        <!-- <label class="block mt-4 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-700 dark:text-gray-400">
              Category
            </span>
            <a href="javascript::" class="reset-cat text-blue-500 dark:text-blue-400">
                Reset
            </a>
          </div>
           Tree View
          <div class="cate-div treeview">
            <?php echo buildCategory(0, $category, ''); ?>
          </div>

        </label> -->

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Status
          </span>
          <select name="status" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
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
    });
</script>
<?php include 'inc/footer.php';?>
