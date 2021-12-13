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
        $select_account = $this->db->prepare('SELECT isAdmin FROM users WHERE user_ID = ?');
        $select_account->execute([$user_id]);
        $select_account_row = $select_account->fetch();
        if ($select_account_row['isAdmin'] == 1) {
            $admin_projects = $this->db_prepare('SELECT project_ID FROM projects WHERE user_ID = ?');
            $admin_projects->execute([$user_id]);
            $admin_projects_row = $admin_projects->fetch();
            foreach ($admin_projects_row as $project) {
                deleteProject($project);
            }
        }
        $query = $this->db->prepare('DELETE FROM users WHERE user_ID = ?');
        $query->execute([$user_id]);
        deleteAddress($user_id);
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
        if (!strlen($new_name) > 0) {
            $new_name = $previous_name;
        }
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

    public function containsCategory($name) {
        $query = $this->db->prepare('SELECT * FROM categories WHERE category_name = ?');
        $query->execute([$name]);
        $row = $query->fetch();
        if ($row) {
            return true;
        } else {
            return false;
        }
    }

    public function createProject($name, $description, $goal, $category_name) {
        $categoryquery = $this->db->prepare('SELECT category_ID FROM categories WHERE category_name = ?');
        $categoryquery->execute(array($category_name));
        $category_row = $categoryquery->fetch();
        $query = $this->db->prepare('INSERT INTO projects (project_name, project_description, number_of_backers, project_goal, category_ID) VALUES (?, ?, ?, ?, ?) ');
        $query->execute([$name, $description, 0, $goal, $category_row['category_ID']]);
    }

    public function deleteProject($project_id) {
        $query_rewards = $this->db->prepare('SELECT reward_ID FROM rewards WHERE project_ID = ?');
        $query_rewards->execute([$project_id]);
        $query_rewards_fetch = $query_rewards->fetch();
        foreach ($query_rewards_fetch as $reward) {
            deleteReward($reward);
        }
        $delete_project = $this->db->prepare('DELETE * FROM projects WHERE project_ID = ?');
        $delete_project->execute([$project_id]);
    }

    public function projectInfo($project_id) {
        $project_query = $this->db->prepare('SELECT * FROM projects WHERE project_ID = ?');
        $project_query->execute([$project_id]);
        return $project_query->fetch();
    }

    public function accountInfo($account_id) {
        $account_query = $this->db->prepare('SELECT projects_supported, rewards_purchased, first_name, last_name, isAdmin, projects_managed, email FROM users WHERE user_ID = ?');
        $account_query->execute([$account_id]);
        $account_row = $account_query->fetch();
        return $account_row;
    }

    public function addressInfo($user_id) {
        $address_query_2 = $this->db->prepare('SELECT city, country, _state, zipcode, address_ID FROM customeraddresses WHERE user_ID = ?');
        $address_query_2->execute([$user_id]);
        $address_row_2 = $address_query_2->fetch();
        if (!$address_row_2) {
            return null;
        } else return $address_row_2;
    }

    public function addAddress($city, $state, $country, $zip, $user_ID) {
        $address_query = $this->db->prepare('INSERT INTO customeraddresses (city, _state, country, zipcode, user_ID) VALUES (?, ?, ?, ?, ?)');
        $address_query->execute([$city, $state, $country, $zip, $user_ID]);
    }

    public function deleteAddress($user_ID) {
        $address_query = $this->db->prepare('DELETE * FROM customeraddresses WHERE user_ID = ?');
        $address_query->execute([$user_ID]);
    }

    public function modifyAddress($city, $state, $country, $zip, $user_ID) {
        $select_address = $this->db->prepare('SELECT * FROM customeraddresses WHERE user_ID = ?');
        $select_reward->execute([$user_id]);
        $select_row = $select_reward->fetch();
        if (!strlen($city) > 0) {
            $city = $select_address['city'];
        } 
        if (!strlen($price) > 0) {
            $state = $select_address['_state'];
        }
        if (!strlen($country) > 0) {
            $country = $select_address['country'];
        } 
        if (!strlen($zip) > 0) {
            $zip = $select_address['zipcode'];
        } 
        $address_query = $this->db->prepare('UPDATE customeraddresses SET city = ?, _state = ?, country = ?, zipcode = ? WHERE user_ID = ?');
        $address_query->execute([$city, $state, $country, $zip, $user_ID]);
    }

    public function containsAddress($user_id) {
        $address_check = $this->db->prepare('SELECT * FROM customeraddresses WHERE user_ID = ?');
        $address_check->execute([$user_id]);
        $address_row = $address_check->fetch();
        if ($address_row) {
            return true;
        } else return false;
    }

    public function addReward($name, $price, $description, $project_id) {
        $reward_query = $this->db->prepare('INSERT INTO rewards (reward_name, reward_price, reward_description, project_ID) VALUES (?, ?, ?, ?)');
        $reward_query->execute([$name, $price, $description, $project_id]);
        $reward_row = $reward_query->fetch();
        return $reward_row;
    }

    public function modifyReward($name, $price, $description, $reward_id) {
        $select_reward = $this->db->prepare('SELECT * FROM rewards WHERE reward_ID = ?');
        $select_reward->execute([$reward_id]);
        $select_row = $select_reward->fetch();
        if (!strlen($name) > 0) {
            $name = $select_row['reward_name'];
        } 
        if (!strlen($price) > 0) {
            $price = $select_row['reward_price'];
        }
        if (!strlen($description) > 0) {
            $description = $select_row['reward_description'];
        } 
        $reward_modify = $this->db->prepare('UPDATE rewards SET reward_name = ?, reward_price = ?, reward_description = ? WHERE reward_ID = ?');
        $reward_modify->execute([$name, $price, $description, $reward_id]);
    }

    public function deleteReward($reward_id) {
        $delete_reward = $this->db_prepare('DELETE * FROM rewards WHERE reward_ID = ?');
        $delete_reward->execute([$reward_id]);
    }

    public function rewardInfo($reward_id) {
        $reward_query = $this->db->prepare('SELECT * FROM rewards WHERE reward_ID = ?');
        $reward_query->execute([$reward_id]);
        return $reward_query->fetch();
    }

    public function placeOrder($date_ordered, $date_fulfilled, $user_id, $reward_id) {
        $address_info = addressInfo($user_id);
        $order_query = $this->db->prepare('INSERT INTO orders (date_ordered, date_fulfilled, user_ID, reward_ID, address_ID) VALUES (?, ?, ?, ?, ?)');
        $order_query->execute([$date_ordered, $date_fulfilled, $user_id, $project_id, $address_info['address_ID']]);
    }
}
