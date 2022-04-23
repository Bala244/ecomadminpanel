<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    date_default_timezone_set('Asia/Kolkata');
    $currdate = date('Y-m-d H:i:s');

    require_once "config/config.php";
    require_once "inc/auth_validate.php";
    function categoryTree($parent_id = 0, $sub_mark = '', $level = 0){
        
        global $conn;
        $query = "SELECT * FROM `category` WHERE `parent_id` = $parent_id ORDER BY `name` ASC";
        $execute = mysqli_query($conn, $query);

        if(mysqli_num_rows($execute) > 0){
            while($row = mysqli_fetch_assoc($execute)){
                // print_r($row); print_r($level);echo '<br>';
                echo '<option value="'.$row['id'].','.$level.'">'.$sub_mark.$row['name'].'</option>';
                categoryTree($row['id'], $sub_mark.'---', $level+1);
            }
        }

    }
    

    if ($_POST) {
      $parent_level_ids = explode(',', $_POST['category']);
      $parent_id = $parent_level_ids[0];
      $level = $parent_level_ids[1];
      // print_r($parent_id);exit;
      $data['name'] = $_POST['name'];
      $data['description'] = $_POST['description'];
      $data['parent_id'] = $parent_id;
      $data['level'] = $level;
      $data['status'] = $_POST['status'];
      $data['created_at'] = $currdate;
      $data['updated_at'] = $currdate;

      $db = getDbInstance();
      // print_r($data);exit;
      $db->where('name', $data['name']);
      $db->where('parent_id', $data['parent_id']);
      $category = $db->get('category');
      
      // print_r(count($category));exit;

      if (count($category) == 0) {
        $resonce = $db->insert('category',$data);
        header('location: categories.php'); 
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
    <form action="" method="post">
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Name</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="name" placeholder="Jane Doe" required>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Description</span>
          <textarea class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="Enter some long form content." name="description"></textarea>
        </label>

        <label class="block mt-4 text-sm">
          <span class="text-gray-700 dark:text-gray-400">
            Category
          </span>
          <select name="category" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
            <option value="0,0">Parent Category</option>
            <?php echo categoryTree(); ?>
          </select>
        </label>

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
              <button class="mr-4 px-12 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-zinc-600 border border-transparent rounded-lg hover:bg-zinc-800 focus:outline-none" onclick="window.location.href='categories.php'">
                Cancel
              </button>
            </div>

            <div>
              <button type="submit" class=" px-12 py-3  font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none">
                Submit
              </button>
            </div>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include 'inc/footer.php';?>
