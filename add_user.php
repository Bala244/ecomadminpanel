<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    require_once "config/config.php";
    require_once "inc/auth_validate.php";
    include "inc/head.php";
    include "inc/header.php";
?>

<main class="h-full pb-16 overflow-y-auto">
  <div class="container grid px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
      Add User
    </h2>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
      <label class="block text-sm">
        <span class="text-gray-700 dark:text-gray-400">Name</span>
        <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Jane Doe">
      </label>

      <div class="mt-4 text-sm">
        <span class="text-gray-700 dark:text-gray-400">
          Account Type
        </span>
        <div class="mt-2">
          <label class="inline-flex items-center text-gray-600 dark:text-gray-400">
            <input type="radio" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" name="accountType" value="personal">
            <span class="ml-2">Personal</span>
          </label>
          <label class="inline-flex items-center ml-6 text-gray-600 dark:text-gray-400">
            <input type="radio" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" name="accountType" value="busines">
            <span class="ml-2">Business</span>
          </label>
        </div>
      </div>

      <label class="block mt-4 text-sm">
        <span class="text-gray-700 dark:text-gray-400">
          Requested Limit
        </span>
        <select class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
          <option>$1,000</option>
          <option>$5,000</option>
          <option>$10,000</option>
          <option>$25,000</option>
        </select>
      </label>

      <label class="block mt-4 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Message</span>
        <textarea class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="Enter some long form content."></textarea>
      </label>
      <div class="flex mt-6 mb-6 justify-end">
          <div>
            <button class="mr-4 px-12 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-zinc-600 border border-transparent rounded-lg hover:bg-zinc-800 focus:outline-none">
              Cancel
            </button>
          </div>

          <div>
            <button class=" px-12 py-3  font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none">
              Submit
            </button>
          </div>

      </div>
    </div>
  </div>
</main>

<?php include 'inc/footer.php';?>
