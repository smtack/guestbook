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

      include("public/views/errors/404.php");
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
    if(isset($_COOKIE['Auth'])) {
      return $this->model->userForAuth($_COOKIE['Auth']);
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
      if(!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = '<p class="message error">Enter a valid Email Address</p>';

        $this->redirect('index');
      } else {
        if($_POST['user_password'] !== $_POST['confirm_password']) {
          $_SESSION['message'] = '<p class="message error">Passwords don\'t match</p>';
  
          $this->redirect('index');
        } else {
          if($this->model->exists('users', array('user_username' => $_POST['user_username']))) {
            $_SESSION['message'] = '<p class="message error">This username is already taken</p>';
  
            $this->redirect('index');
          } else if($this->model->exists('users', array('user_email' => $_POST['user_email']))) {
            $_SESSION['message'] = '<p class="message error">This email address is already in use</p>';
  
            $this->redirect('index');
          } else {
            $signup_array = array(
              'user_name' => htmlentities($_POST['user_name']),
              'user_username' => htmlentities($_POST['user_username']),
              'user_email' => htmlentities($_POST['user_email']),
              'user_password' => password_hash($_POST['user_password'], PASSWORD_BCRYPT)
            );
        
            if($this->model->signupUser($signup_array)) {
              $this->redirect("home");
            } else {
              $this->redirect("index");
            }
          }
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
      $posts = $this->model->getHomePosts($user->user_id);

      $this->loadPage($user, "home", array('user' => $user, 'posts' => $posts));
    }
  }
  
  private function logOut() {
    $this->model->logoutUser($_COOKIE['Auth']);

    $this->redirect("index");
  }

  private function updateProfile() {
    $user = $this->checkUser();

    $page_title = "Update Profile";

    if($user === false) {
      $this->redirect("index");
    } else {
      $this->loadPage($user, "update-profile", array('user' => $user), $page_title);
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
        if(!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
          $_SESSION['update_message'] = '<p class="message error">Enter a valid Email Address</p>';
        
          $this->redirect('update-profile');
        } else {
          if($this->model->exists('users', array('user_username' => $_POST['user_username'])) && $_POST['user_username'] !== $user->user_username) {
            $_SESSION['update_message'] = '<p class="message error">This username is already taken</p>';
          
            $this->redirect('update-profile');
          } else if($this->model->exists('users', array('user_email' => $_POST['user_email'])) && $_POST['user_email'] !== $user->user_email) {
            $_SESSION['update_message'] = '<p class="message error">This email address is already in use</p>';
          
            $this->redirect('update-profile');
          } else {
            $update_array = array(
              'user_name' => htmlentities($_POST['user_name']),
              'user_username' => htmlentities($_POST['user_username']),
              'user_email' => htmlentities($_POST['user_email'])
            );
    
            if($this->model->updateInfo($update_array, $user->user_id)) {
              $_SESSION['update_message'] = '<p class="message notice">Profile successfully updated</p>';
              
              $this->redirect('update-profile');
            } else {
              $_SESSION['update_message'] = '<p class="message error">Unable to update information</p>';
    
              $this->redirect('update-profile');
            }
          }
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
  
            if($this->model->uploadProfilePicture($profile_picture_array, $user->user_id)) {
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
        if(!password_verify($_POST['current_password'], $user->user_password)) {
          $_SESSION['password_message'] = '<p class="message error">Enter your current password correctly</p>';

          $this->redirect('update-profile');
        } else {
          if($_POST['new_password'] !== $_POST['confirm_password']) {
            $_SESSION['password_message'] = '<p class="message error">Passwords don\'t match</p>';

            $this->redirect('update-profile');
          } else {
            $password_array = array('user_password' => password_hash($_POST['new_password'], PASSWORD_BCRYPT));
      
            if($this->model->updatePassword($password_array, $user->user_id)) {
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
      if($this->model->deleteProfile($user->user_id)) {
        $this->logOut();
      } else {
        $_SESSION['delete_message'] = '<p class="message error">Unable to delete profile</p>';

        $this->redirect("update-profile");
      }
    }
  }

  private function profile() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect('index');
    } else {
      if(isset($_GET['query'])) {
        $profile = $_GET['query'];
  
        if($profile_info = $this->model->getProfile($profile)) {
          $profile_data = $this->model->getProfileInfo($user, $profile_info['user_id']);
          $posts = $this->model->getUsersPosts($profile_info['user_id']);
          $page_title = $profile_info['user_name'] . "'s Profile";
  
          $this->loadPage($user, "profile", array("user" => $user, "profile_info" => $profile_info, "profile_data" => $profile_data, "posts" => $posts), $page_title);
        } else {
          $this->redirect('home');
        }
      }
    }
  }

  private function searchUsers() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect('index');
    } else {
      if(isset($_POST['search-users'])) {
        $user_keywords = $_POST['search-users'];
      }

      if(isset($user_keywords)) {
        $results = $this->model->searchUsers($user_keywords);
        $page_title = "Search Users: " . $user_keywords;
  
        $this->loadPage($user, "search-users", array("user" => $user, "user_keywords" => $user_keywords, "results" => $results), $page_title);
      } else {
        $this->redirect('home');
      }
    }
  }

  private function follow() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect('index');
    } else {
      if($this->model->follow($user, htmlentities($_GET['query']))) {
        $this->redirect('home');
      } else {
        $this->redirect('home');
      }
    }
  }

  private function unfollow() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect('index');
    } else {
      if($this->model->unfollow($user, htmlentities($_GET['query']))) {
        $this->redirect('home');
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
      $this->loadPage($user, "create", array('user' => $user), $page_title);
    }
  }

  private function newPost() {
    $user = $this->checkUser();

    if($user === false) {
      $this->redirect("index");
    } else {
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
                'post_slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', htmlentities($_POST['post_title'])))) . '-' . rand(0, 100),
                'post_image' => $file_name,
                'post_content' => htmlentities($_POST['post_content']),
                'post_by' => $user->user_id
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
            'post_slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', htmlentities($_POST['post_title'])))) . '-' . rand(0, 100),
            'post_content' => htmlentities($_POST['post_content']),
            'post_by' => $user->user_id
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
    $posts = $this->model->getPosts();

    $this->loadPage($user, "posts", array('user' => $user, 'posts' => $posts), $page_title);
  }

  private function post() {
    $user = $this->checkUser();

    if(isset($_GET['query'])) {
      $post = $_GET['query'];

      if($post_data = $this->model->getPost($post)) {
        $comments = $this->model->getComments($post_data['post_id']);
        $page_title = $post_data['post_title'];

        $this->loadPage($user, "post", array('user' => $user, 'post_data' => $post_data, 'comments' => $comments), $page_title);
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
          $page_title = "Edit Post: " . $post_data['post_title'];

          if($user->user_id !== $post_data['user_id']) {
            $this->redirect('home');
          } else {
            $this->loadPage($user, "edit", array('user' => $user, 'post_data' => $post_data), $page_title);
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

      $post_data = $this->model->getPost($post);

      if($user->user_id !== $post_data['user_id']) {
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
                  'post_slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', htmlentities($_POST['post_title'])))) . '-' . rand(0, 100),
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
              'post_slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', htmlentities($_POST['post_title'])))) . '-' . rand(0, 100),
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

      if($user->user_id !== $post_data['user_id']) {
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

      if(empty($_POST['comment_text'])) {
        $_SESSION['comment_message'] = '<p class="message error">Enter a comment</p>';

        $this->redirect('post/' . $post);
      } else {
        $comment_array = array(
          'comment_text' => htmlentities($_POST['comment_text']),
          'comment_post' => $post_data['post_id'],
          'comment_by' => $user->user_id
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

  private function searchPosts() {
    $user = $this->checkUser();

    if(isset($_POST['search'])) {
      $post_keywords = $_POST['search'];
    }

    if(isset($post_keywords)) {
      $results = $this->model->searchPosts($post_keywords);
      $page_title = "Search: " . $post_keywords;
  
      $this->loadPage($user, "search-posts", array("user" => $user, "post_keywords" => $post_keywords, "results" => $results), $page_title);
    } else {
      $this->redirect('home');
    }
  }
}