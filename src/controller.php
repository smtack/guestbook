<?php
require 'model.php';
require 'router.php';

class Controller {
  private $model;
  private $router;

  function __construct() {
    $this->model = new Model();
    $this->router = new Router();

    $queryParams = false;

    if(strlen($_GET['query']) > 0) {
      $queryParams = explode("/", $_GET['query']);
    }

    $page = $_GET['page'];

    $endpoint = $this->router->lookup($page);

    if($endpoint === false) {
      header("HTTP/1.0 404 Not Found");
    } else {
      $this->$endpoint($queryParams);
    }
  }

  private function redirect($url) {
    header("Location: /" . $url);
  }

  private function loadView($view, $data = null, $page_title = null) {
    if(is_array($data)) {
      extract($data);
    }

    require 'public/views/' . $view . '.php';
  }

  private function loadPage($user, $view, $data = null, $page_title = null) {
    $this->loadView('/includes/header', array('user' => $user), $page_title);
    $this->loadView($view, $data, $page_title);
    $this->loadView('includes/footer');
  }

  private function checkUser() {
    if(isset($_SESSION['user_username'])) {
      return $_SESSION['user_username'];
    } else {
      return false;
    }
  }

  private function index() {
    $user = $this->checkUser();

    $page_title = "Sign Up";

    if($user !== false) {
      $this->redirect("home");
    } else {
      $this->loadPage($user, "index", array(), $page_title);
    }
  }

  private function signUp() {
    if(empty($_POST['user_name']) || empty($_POST['user_username']) || empty($_POST['user_email']) || empty($_POST['user_password']) || empty($_POST['confirm_password'])) {
      $_SESSION['message'] = '<p class="message error">Fill in all fields</p>';

      $this->redirect('index');
    } else {
      if($_POST['user_password'] !== $_POST['confirm_password']) {
        $_SESSION['message'] = '<p class="message error">Passwords don\'t match</p>';

        $this->redirect('index');
      } else {
        $password_hash = password_hash($_POST['user_password'], PASSWORD_BCRYPT);

        $signup_array = array(
          'user_name' => htmlentities($_POST['user_name']),
          'user_username' => htmlentities($_POST['user_username']),
          'user_email' => htmlentities($_POST['user_email']),
          'user_password' => $password_hash
        );
    
        if($this->model->signupUser($signup_array)) {
          $_SESSION['user_username'] = $signup_array['user_username'];
          $_SESSION['logged_in'] = true;
    
          $this->redirect("home");
        } else {
          $this->redirect("index");
        }
      }
    }
  }

  private function login() {
    $user = $this->checkUser();

    $page_title = "Log In";

    if($user !== false) {
      $this->redirect("home");
    } else {
      $this->loadPage($user, "login", array(), $page_title);
    }
  }

  private function loginUser() {
    if(empty($_POST['user_username']) || empty($_POST['user_password'])) {
      $_SESSION['login_message'] = '<p class="message error">Enter your Username and Password</p>';
      
      $this->redirect('login');
    } else {
      $login_array = array(
        'user_username' => $_POST['user_username'],
        'user_password' => $_POST['user_password']
      );
  
      if($this->model->attemptLogin($login_array)) {
        $_SESSION['user_username'] = $login_array['user_username'];
        $_SESSION['logged_in'] = true;
  
        $this->redirect("home");
      } else {
        $_SESSION['login_message'] = '<p class="message error">Username or Password Incorrect</p>';

        $this->redirect("login");
      }
    }
  }

  private function home() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect("index");
    } else {
      $user_info = $this->model->getUserInfo();
      $posts = $this->model->getUsersPosts($user_info['user_id']);

      $this->loadPage($user, "home", array('user' => $user, 'user_info' => $user_info, 'posts' => $posts));
    }
  }
  
  private function logOut() {
    $this->model->logoutUser();
    $this->redirect("index");
  }

  private function updateProfile() {
    $user = $this->checkUser();

    $page_title = "Update Profile";

    if($user === false) {
      $this->redirect("index");
    } else {
      $user_info = $this->model->getUserInfo();

      $this->loadPage($user, "update-profile", array('user' => $user, 'user_info' => $user_info), $page_title);
    }
  }

  private function updateInfo() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect("index");
    } else {
      if(empty($_POST['user_name']) || empty($_POST['user_username']) || empty($_POST['user_email'])) {
        $_SESSION['update_message'] = '<p class="message error">Fill in all fields</p>';
        
        $this->redirect('update-profile');
      } else {
        $user_info = $this->model->getUserInfo();

        $update_array = array(
          'user_name' => htmlentities($_POST['user_name']),
          'user_username' => htmlentities($_POST['user_username']),
          'user_email' => htmlentities($_POST['user_email'])
        );

        if($this->model->updateInfo($update_array, $user_info['user_id'])) {
          $_SESSION['user_username'] = $update_array['user_username'];
          $_SESSION['update_message'] = '<p class="message notice">Profile successfully updated</p>';
          
          $this->redirect('update-profile');
        } else {
          $_SESSION['update_message'] = '<p class="message error">Unable to update information</p>';

          $this->redirect('update-profile');
        }
      }
    }
  }

  private function uploadProfilePicture() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect('index');
    } else {
      if(empty($_FILES['user_profile_picture']['name'])) {
        $_SESSION['picture_message'] = '<p class="message error">Select a file to upload</p>';

        $this->redirect('update-profile');
      } else {
        $user_info = $this->model->getUserInfo();

        $target_dir = "uploads/profile-pictures/";
        $file_name = basename($_FILES['user_profile_picture']['name']);
        $path = $target_dir . $file_name;
        $file_type = pathinfo($path, PATHINFO_EXTENSION);
        $allow_types = array('jpg', 'png');
  
        if(in_array($file_type, $allow_types)) {
          if(move_uploaded_file($_FILES['user_profile_picture']['tmp_name'], $path)) {
            $profile_picture_array = array(
              'user_profile_picture' => $file_name
            );
  
            if($this->model->uploadProfilePicture($profile_picture_array, $user_info['user_id'])) {
              $_SESSION['picture_message'] = '<p class="message notice">Profile picture uploaded</p>';

              $this->redirect('update-profile');
            } else {
              $_SESSION['picture_message'] = '<p class="message error">Unable to upload profile picture</p>';

              $this->redirect('update-profile');
            }
          } else {
            $_SESSION['picture_message'] = '<p class="message error">Unable to upload profile picture</p>';

            $this->redirect('update-profile');
          }
        } else {
          $_SESSION['picture_message'] = '<p class="message error">File type not supported</p>';

          $this->redirect('update-profile');
        }
      }
    }
  }

  private function changePassword() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect("index");
    } else {
      if(empty($_POST['current_password']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
        $_SESSION['password_message'] = '<p class="message error">Fill in all fields</p>';

        $this->redirect('update-profile');
      } else {
        $user_info = $this->model->getUserInfo();

        if(!password_verify($_POST['current_password'], $user_info['user_password'])) {
          $_SESSION['password_message'] = '<p class="message error">Enter your current password correctly</p>';

          $this->redirect('update-profile');
        } else {
          if($_POST['new_password'] !== $_POST['confirm_password']) {
            $_SESSION['password_message'] = '<p class="message error">Passwords don\'t match</p>';

            $this->redirect('update-profile');
          } else {
            $password_hash = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

            $password_array = array(
              'user_password' => $password_hash
            );
      
            if($this->model->updatePassword($password_array, $user_info['user_id'])) {
              $_SESSION['password_message'] = '<p class="message notice">Password successfully updated</p>';

              $this->redirect('update-profile');
            } else {
              $_SESSION['password_message'] = '<p class="message error">Unable to change Password</p>';

              $this->redirect('update-profile');
            }
          }
        }
      }
    }
  }

  private function deleteUser() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect("index");
    } else {
      $user_info = $this->model->getUserInfo();

      if($this->model->deleteProfile($user_info)) {
        $this->logOut();
      } else {
        $_SESSION['delete_message'] = '<p class="message error">Unable to delete profile</p>';

        $this->redirect("update-profile");
      }
    }
  }

  private function profile() {
    $user = $this->checkUser();

    if(isset($_GET['query'])) {
      $profile = $_GET['query'];

      if($profile_info = $this->model->getProfile($profile)) {
        $posts = $this->model->getUsersPosts($profile_info['user_id']);
        $page_title = $profile_info['user_name'] . "'s Profile";

        $this->loadPage($user, "profile", array("user" => $user, "profile_info" => $profile_info, "posts" => $posts), $page_title);
      } else {
        $this->redirect('home');
      }
    }  
  }

  private function create() {
    $user = $this->checkUser();
    $page_title = "Create a Post";

    if($user === false) {
      $this->redirect("index");
    } else {
      $user_info = $this->model->getUserInfo();

      $this->loadPage($user, "create", array('user' => $user, 'user_info' => $user_info), $page_title);
    }
  }

  private function newPost() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect("index");
    } else {
      $user_info = $this->model->getUserInfo();

      if(empty($_POST['post_title']) || empty($_POST['post_content'])) {
        $_SESSION['post_message'] = '<p class="message error">Enter a title and some text</p>';

        $this->redirect('create');
      } else {
        if(!empty($_FILES['post_image']['name'])) {
          $target_dir = "uploads/post-images/";
          $file_name = basename($_FILES['post_image']['name']);
          $path = $target_dir . $file_name;
          $file_type = pathinfo($path, PATHINFO_EXTENSION);
          $allow_types = array('jpg', 'png', 'gif');

          if(in_array($file_type, $allow_types)) {
            if(move_uploaded_file($_FILES['post_image']['tmp_name'], $path)) {
              $post_array = array(
                'post_title' => htmlentities($_POST['post_title']),
                'post_image' => $file_name,
                'post_content' => htmlentities($_POST['post_content']),
                'post_by' => $user_info['user_id']
              );

              if($this->model->newPost($post_array)) {
                $this->redirect('home');
              } else {
                $_SESSION['post_message'] = '<p class="message error">Unable to make post</p>';

                $this->redirect('create');
              }
            } else {
              $_SESSION['post_message'] = '<p class="message error">Unable to upload image</p>';

              $this->redirect('create');
            }
          } else {
            $_SESSION['post_message'] = '<p class="message error">This file type is not supported</p>';

            $this->redirect('create');
          }
        } else {
          $post_array = array(
            'post_title' => htmlentities($_POST['post_title']),
            'post_content' => htmlentities($_POST['post_content']),
            'post_by' => $user_info['user_id']
          );

          if($this->model->newPost($post_array)) {
            $this->redirect('home');
          } else {
            $_SESSION['post_message'] = '<p class="message error">Unable to make post</p>';

            $this->redirect('create');
          }
        }
      }
    }
  }

  private function posts() {
    $user = $this->checkUser();
    $page_title = "All Posts";

    $user_info = $this->model->getUserInfo();
    $posts = $this->model->getPosts();

    $this->loadPage($user, "posts", array('user' => $user, 'user_info' => $user_info, 'posts' => $posts), $page_title);
  }

  private function post() {
    $user = $this->checkUser();

    if(isset($_GET['query'])) {
      $post = $_GET['query'];

      if($post_data = $this->model->getPost($post)) {
        $user_info = $this->model->getUserInfo();
        $comments = $this->model->getComments($post);
        $page_title = $post_data['post_title'];

        $this->loadPage($user, "post", array('user' => $user, 'user_info' => $user_info, 'post_data' => $post_data, 'comments' => $comments), $page_title);
      } else {
        $this->redirect('home');
      }
    }
  }

  private function edit() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect("index");
    } else {
      if(isset($_GET['query'])) {
        $post = $_GET['query'];

        if($post_data = $this->model->getPost($post)) {
          $user_info = $this->model->getUserInfo();
          $page_title = "Edit Post: " . $post_data['post_title'];

          if($user_info['user_id'] !== $post_data['user_id']) {
            $this->redirect('home');
          } else {
            $this->loadPage($user, "edit", array('user' => $user, 'user_info' => $user_info, 'post_data' => $post_data), $page_title);
          }
        } else {
          $this->redirect('home');
        }
      }
    }
  }

  private function editPost() {
    $user = $this->checkuser();

    if($user === false) {
      $this->redirect('index');
    } else {
      $post = $_GET['query'];

      $user_info = $this->model->getUserInfo();
      $post_data = $this->model->getPost($post);

      if($user_info['user_id'] !== $post_data['user_id']) {
        $this->redirect('home');
      } else {
        if(empty($_POST['post_title']) || empty($_POST['post_content'])) {
          $_SESSION['edit_message'] = '<p class="message error">Enter a title and some text</p>';
  
          $this->redirect('edit/' . $post);
        } else {
          if(!empty($_FILES['post_image']['name'])) {
            $target_dir = "uploads/post-images/";
            $file_name = basename($_FILES['post_image']['name']);
            $path = $target_dir . $file_name;
            $file_type = pathinfo($path, PATHINFO_EXTENSION);
            $allow_types = array('jpg', 'png', 'gif');
  
            if(in_array($file_type, $allow_types)) {
              if(move_uploaded_file($_FILES['post_image']['tmp_name'], $path)) {
                $update_array = array(
                  'post_title' => htmlentities($_POST['post_title']),
                  'post_image' => $file_name,
                  'post_content' => htmlentities($_POST['post_content']),
                );
  
                if($this->model->editPost($update_array, $post_data['post_id'])) {
                  $this->redirect('home');
                } else {
                  $_SESSION['edit_message'] = '<p class="message error">Unable to update post</p>';
  
                  $this->redirect('edit/' . $post);
                }
              } else {
                $_SESSION['edit_message'] = '<p class="message error">Unable to upload image</p>';
  
                $this->redirect('edit/' . $post);
              }
            } else {
              $_SESSION['edit_message'] = '<p class="message error">This file type is not supported</p>';
  
              $this->redirect('edit/' . $post);
            }
          } else {
            $update_array = array(
              'post_title' => htmlentities($_POST['post_title']),
              'post_content' => htmlentities($_POST['post_content']),
            );
  
            if($this->model->editPost($update_array, $post_data['post_id'])) {
              $this->redirect('home');
            } else {
              $_SESSION['edit_message'] = '<p class="message error">Unable to update post</p>';
  
              $this->redirect('edit/' . $post);
            }
          }
        }
      }
    }
  }

  private function deletePost() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect('index');
    } else {
      $post = $_GET['query'];

      $post_data = $this->model->getPost($post);
      $user_info = $this->model->getUserInfo();

      if($user_info['user_id'] !== $post_data['user_id']) {
        $this->redirect('home');
      } else {
        $image_dir = "uploads/post-images/";
        $image = $post_data['post_image'];
        $file = $image_dir . $image;
  
        if($this->model->deletePost($post_data['post_id'])) {
          if(file_exists($file)) {
            unlink($file);
          }
  
          $this->redirect('home');
        } else {
          $_SESSION['delete_message'] = '<p class="message error">Unable to delete post</p>';
  
          $this->redirect('edit/' . $post);
        }
      }
    }
  }

  private function comment() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect('index');
    } else {
      $post = $_GET['query'];

      $post_data = $this->model->getPost($post);
      $user_info = $this->model->getUserInfo();

      if(empty($_POST['comment_text'])) {
        $_SESSION['comment_message'] = '<p class="message error">Enter a comment</p>';

        $this->redirect('post/' . $post);
      } else {
        $comment_array = array(
          'comment_text' => htmlentities($_POST['comment_text']),
          'comment_post' => $post_data['post_id'],
          'comment_by' => $user_info['user_id']
        );

        if($this->model->newComment($comment_array)) {
          $this->redirect('post/' . $post);
        } else {
          $_SESSION['comment_message'] = '<p class="message error">Unable to post comment</p>';

          $this->redirect('post/' . $post);
        }
      }
    }
  }

  private function search() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect('index');
    } else {
      if(isset($_POST['search'])) {
        $keywords = $_POST['search'];
      }

      $results = $this->model->search($keywords);
      $page_title = "Search: " . $keywords;

      $this->loadPage($user, "search", array("results" => $results), $page_title);
    }
  }
}