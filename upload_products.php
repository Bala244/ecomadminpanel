<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    date_default_timezone_set('Asia/Kolkata');
    $currdate = date('Y-m-d H:i:s');

    require_once "config/config.php";
    require_once "inc/auth_validate.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        // echo '<pre>';print_r($_FILES);echo '</pre>';exit;

        if ($_FILES["file"]["size"] > 0){

            $filename = $_FILES['file']['name'];

            if (($handle = fopen($filename, "r")) !== FALSE){

                $row = 1;
                $i = 0;

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE){
                    $num = count($data);

                    // echo '<pre>';print_r($data);echo '</pre>';exit;

                    if($row > 1){

                        echo '<pre>';print_r($data);echo '</pre>';exit;

                        $category_id = '';
                        $sub_category_id_1 = '';
                        $sub_category_id_2 = '';
                        $sub_category_id_3 = '';

                        $category = $data[3];
                        $sub_category_1 = $data[4];
                        $sub_category_2 = $data[5];
                        $sub_category_3 = $data[6];

                        if($category != ''){
                            $db = getDbInstance();
                            $db->where('name', $category);
                            $category_details = $db->get('category');

                            if(count($category_details) > 0){

                            }
                        }

                        $data_to_db = array();
                        $data_to_db['name'] = $data[1];
                        $data_to_db['description'] = $data[2];
                        $data_to_db['status'] = 1;
                        $data_to_db['created_at'] = $currdate;
                        $data_to_db['created_by'] = $_SESSION['user_id'];

                        $db = getDbInstance();
                        $last_id = $db->insert('products', $data_to_db);

                        if ($last_id) {
                            $_SESSION['success'] = "Products Uploaded Successfully";
                            header('Location: products.php');exit;
                        }else{
                            $_SESSION['failure'] = "Products Not Uploaded. Please Try Again.";
                            header('Location: upload_products.php');exit;
                        }
                    }

                    $row++;
                }
            }
        }else{
            $_SESSION['failure'] = "Employee Upload File. Please Check the File.";
            header("Location:upload_products.php");exit;
        }
    }


    include "inc/head.php";
    include "inc/header.php";
?>



<main class="h-full pb-16 overflow-y-auto">
  <div class="container grid px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
      Upload Products
    </h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <label class="block text-sm">
          <span class="text-gray-700 dark:text-gray-400">Select File</span>
          <input type="file" name="file" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" required>
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

<?php include 'inc/footer.php';?>
