<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    require_once "config/config.php";
    require_once "inc/auth_validate.php";


    $result = mysqli_query($conn, "SELECT * FROM category ORDER BY parent_id, id");


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
                    $html .= "<li class=''><div class='flex py-1'><span class='text-sm span1'><a href='edit_category.php?id=". $category['categories'][$cat_id]['id'] ."'>" . $category['categories'][$cat_id]['name'] . "</a></span><span class='text-sm span-custom1'>10</span><span class='text-sm span-custom2'>".$category['categories'][$cat_id]['status'] . "</span> </div></li> \n";
                }
                if (isset($category['parent_cats'][$cat_id])) {
                    $html .= "<li class=''><div class='flex py-1'><span class='custom-plus toggle-click' style='position: absolute;left: -14px;'>+</span><span class='text-sm span1'><a href='edit_category.php?id=". $category['categories'][$cat_id]['id'] ."'>" . $category['categories'][$cat_id]['name'] . "</a></span><span class='text-sm span-custom1'>10</span><span class='text-sm span-custom2'>".$category['categories'][$cat_id]['status'] . "</span></div> \n";
                    $html .= buildCategory($cat_id, $category, 'main-ul custom-hidden');
                    $html .= "</li> \n";
                }
            }
            $html .= "</ul> \n";
        }
        return $html;
    }

    include "inc/head.php";
    include "inc/header.php";
    // echo '<pre>';print_r($execute);echo '</pre>';exit;
?>

<main class="h-full pb-16 overflow-y-auto">
    <div class="container grid px-6 mx-auto">
        <div class="flex justify-between">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Category</h2>
            <a href="add_category.php" class="my-6 px-12 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none">Add Category</a>
        </div>

        <div class="w-full m-auto overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3 w-2/12">No Of Products</th>
                            <th class="px-4 py-3 w-2/12">Status</th>
                            <!-- <th class="px-4 py-3 w-2/12">Actions</th> -->
                        </tr>
                    </thead>
                </table>
                <div class="treeview">
                    <?php echo buildCategory(0, $category, ''); ?>
                </div>
            </div>
 
        </div>
    </div>
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
