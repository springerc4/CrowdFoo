<?php
require_once('settings.php');

class SqlOperation {
    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    public function contains($email, $password = null) {
        $query = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $query->execute([$email]);
        $row = $query->fetch();
        if ($row) {
            if ($password == null) {
                return true;
            }
            else {
                return $row['user_password'] == $password;
            }
        }
        else {
            return false;
        }
    }

    public function deleteAccount($user_id) {
        $query = $this->db->prepare('DELETE FROM users WHERE user_ID = ?');
        $query->execute([$user_id]);
        $_SESSION['logged'] = 'false';
    }

    public function modifyAccount($email, $password, $fname, $lname, $admin) {
        $current_id = $_SESSION['userID'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<div class="alert alert-warning" role="alert">Please Enter a Valid Email Address</div>';
        }
        if ((strlen($password) < 8 || strlen($password) > 16) && !strlen($password) == 0) {
            echo '<div class="alert alert-warning" role="alert">Password Must Be Between 8 and 16 Characters</div>';
        }
        $modifyquery = $this->db->prepare('UPDATE users SET email = ?, user_password = ?, first_name = ?, last_name = ?, isAdmin = ? WHERE user_ID = ?');
        $modifyquery->execute([$email, $password, $fname, $lname, $admin, $current_id]);
        $_SESSION['email'] = $email;
        $_SESSION['admin'] = $admin;
        $_SESSION['password'] = $password;
        $_SESSION['firstname'] = $fname;
        $_SESSION['lastname'] = $lname;
    }

    public function modifyCategory($previous_name, $new_name) {
        $categoryquery = $this->db->prepare('SELECT category_ID FROM categories WHERE category_name = ?');
        $categoryquery->execute($previous_name);
        $category_row = $categoryquery->fetch();
        $modify_category_query = $this->db->prepare('UPDATE categories SET category_name = ? WHERE category_ID = ?');
        $modify_category_query->execute([$new_name, $category_row['category_ID']]);
    }

    public function setDefaultSession() {
        $_SESSION['email'] = null;
        $_SESSION['admin'] = 0;
        $_SESSION['password'] = null;
        $_SESSION['firstname'] = null;
        $_SESSION['lastname'] = null;
        $_SESSION['userID'] = 0;
    }

    public function createCategory($name) {
        if (containsCategory($name)) {
            echo '<div class="alert alert-warning" role="alert">This category already exists</div>';
        } else {
            $query = $this->db->prepare('INSERT INTO categories (category_name) VALUES (?)');
            $query->execute([$name]);
        }
    }

    public static function containsCategory($name) {
        $query = $db->prepare('SELECT * FROM categories WHERE category_name = ?');
        $query->execute([$name]);
        $row = $query->fetch();
        if ($row) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteCategory($name) {
        if (!containsCategory($name)) {
            echo '<div class="alert alert-warning" role="alert">Category Not Found</div>';
        } else {
            $delete_category_query = $this->db->prepare('DELETE * FROM categories WHERE category_name = ?');
            $delete_category_query->execute([$name]);
        }
    }

    public function createProject($name, $description, $goal, $category_name) {
        $categoryquery = $this->db->prepare('SELECT category_ID FROM categories WHERE category_name = ?');
        $categoryquery->execute(array($category_name));
        $category_row = $categoryquery->fetch();
        $query = $this->db->prepare('INSERT INTO projects (project_name, project_description, number_of_backers, project_goal, category_ID) VALUES (?, ?, ?, ?, ?) ');
        $query->execute([$name, $description, 0, $goal, $category_row['category_ID']]);
    }

    public function getProject($id){
        $projectQuery = $this->db->prepare('SELECT * FROM projects WHERE project_ID = ?');
        $projectQuery->execute(array($id));
        $projectArray = $projectQuery->fetch();
        return $projectArray;
    }

    public function getRewards($projectID){
        $rewardArray = array();
        $query = $this->db->prepare('SELECT * FROM rewards WHERE project_ID = ?');
        $query->execute(array($projectID));
        while($reward = $query->fetch()){ 
           array_push($rewardArray, $reward); 
        }
        return $rewardArray;
        
    }

}
