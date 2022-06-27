<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    require_once "config/config.php";
    require_once "inc/auth_validate.php";


    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        // echo '<pre>';print_r($_FILES);echo '</pre>';exit;

        if(isset($_FILES['profile_image']) && $_FILES['profile_image']['name'] != ''){
            $filename = basename($_FILES['profile_image']['name']);
            $temp_path = $_FILES['profile_image']['tmp_name'];
            $upload_folder = "uploads/users/".$filename;

            if(move_uploaded_file($temp_path, $upload_folder)){
                $filepath = $upload_folder;
            }else{
                $filepath = '';
                $_SESSION['failure'] = "Profile Image not Uploaded";
            }
        }

        $db_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $name = isset($_POST['name']) && $_POST['name'] != '' ? $_POST['name'] : '';
        $email = isset($_POST['email']) && $_POST['email'] != '' ? $_POST['email'] : '';
        $password = $db_password;
        $mobile_no = isset($_POST['mobile_no']) && $_POST['mobile_no'] != '' ? $_POST['mobile_no'] : '';
        $pin_no = isset($_POST['pin_no']) && $_POST['pin_no'] != '' ? $_POST['pin_no'] : '';
        $gender = isset($_POST['gender']) && $_POST['gender'] != '' ? $_POST['gender'] : '';
        $address = isset($_POST['address']) && $_POST['address'] != '' ? $_POST['address'] : '';
        $profile_image = $filepath;
        $created_at = date('Y-m-d H:i:s');
        $created_by = $_SESSION['user_id'];

        $query1 = "INSERT INTO `users` (`name`, `email`, `password`, `mobile_no`, `pin_no`, `admin_type`, `gender`, `address`, `profile_image`, `is_active`, `created_at`, `created_by`)
            VALUES ('".$name."', '".$email."', '".$password."', '".$mobile_no."', '".$pin_no."', 'admin', '".$gender."', '".$address."', '".$filepath."', '1', '".$created_at."', '".$created_by."')";
        $execute1 = mysqli_query($conn, $query1);

        if($execute1){
            $_SESSION['success'] = 'User Created Successfully';
            header("Location:users.php");exit;
        }else{
            $_SESSION['failure'] = 'User Not Created. Please Try Again.';
            header("Location:users.php");exit;
        }
    }
?>
<?php
    include "inc/head.php";
    include "inc/header.php";
?>


<main class="h-full pb-16 overflow-y-auto">
  <div class="container grid px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
      Add User
    </h2>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
          <label class="block text-sm">
            <span class="text-gray-700 dark:text-gray-400">Name</span>
            <input type="text" name="name" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Name" autocomplete="Off">
          </label>

          <label class="block text-sm">
            <span class="text-gray-700 dark:text-gray-400">Email</span>
            <input type="text" name="email" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Email" autocomplete="Off">
          </label>

          <label class="block text-sm">
            <span class="text-gray-700 dark:text-gray-400">Password</span>
            <input type="password" name="password" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Password" autocomplete="Off">
          </label>

          <label class="block text-sm">
            <span class="text-gray-700 dark:text-gray-400">Mobile Number</span>
            <input type="text" name="mobile_no" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Mobile Number" autocomplete="Off">
          </label>

          <label class="block text-sm">
            <span class="text-gray-700 dark:text-gray-400">Pin No</span>
            <input type="text" name="pin_no" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Pin No" autocomplete="Off">
          </label>

          <div class="mt-4 text-sm">
            <span class="text-gray-700 dark:text-gray-400">
              Gender
            </span>
            <div class="mt-2">
              <label class="inline-flex items-center text-gray-600 dark:text-gray-400">
                <input type="radio" name="gender" value="male" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                <span class="ml-2">Male</span>
              </label>
              <label class="inline-flex items-center ml-6 text-gray-600 dark:text-gray-400">
                <input type="radio" name="gender" value="female" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                <span class="ml-2">Female</span>
              </label>
            </div>
          </div>

          <label class="block mt-4 text-sm">
            <span class="text-gray-700 dark:text-gray-400">Address</span>
            <textarea name="address" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="Address"></textarea>
          </label>

          <label class="block text-sm">
            <span class="text-gray-700 dark:text-gray-400">Profile Picture</span>
            <input type="file" name="profile_image" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" autocomplete="Off">
          </label>

          <div class="flex mt-6 mb-6 justify-end">
              <div>
                <button class="mr-4 px-10 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-zinc-600 border border-transparent rounded-lg hover:bg-zinc-800 focus:outline-none">
                  Cancel
                </button>
              </div>

              <div>
                <button class=" px-10 py-3  font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none">
                  Submit
                </button>
              </div>

          </div>
        </div>
    </form>
  </div>
</main>

<?php include 'inc/footer-links.php';?>
<?php include 'inc/footer.php';?>
