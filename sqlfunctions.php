<?php
require_once('settings.php');

class SqlOperation {
    private $db;

    function __construct($db) {
        $this->db = $db;
    }

##########################################################--- User ---########################################################################

    public function contains($email, $password = null) {
        $query = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $query->execute([$email]);
        $row = $query->fetch();
        if ($row && $row['user_ID'] != $_SESSION['userID']) {
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
            $admin_projects = $this->db->prepare('SELECT project_ID FROM projects WHERE user_ID = ?');
            $admin_projects->execute([$user_id]);
            while ($admin_projects_row = $admin_projects->fetch()) {
                $this->deleteProject($admin_projects_row['project_ID']);
            }
        }
        $query = $this->db->prepare('DELETE FROM users WHERE user_ID = ?');
        $query->execute([$user_id]);
        $this->deleteAddress($user_id);
        $_SESSION['logged'] = 'false';
        header('lcoation: index.php');
    }

    public function modifyAccount($email, $password, $fname, $lname, $admin) {
        $current_id = $_SESSION['userID'];
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

    public function accountInfo($account_id) {
        $account_query = $this->db->prepare('SELECT projects_supported, rewards_purchased, first_name, last_name, isAdmin, projects_managed, email FROM users WHERE user_ID = ?');
        $account_query->execute([$account_id]);
        $account_row = $account_query->fetch();
        return $account_row;
    }

    ##########################################################--- category ---########################################################################


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
        if ($this->containsCategory($name)) {
            echo '<div class="alert alert-warning" role="alert">This category already exists</div>';
        } else {
            $query = $this->db->prepare('INSERT INTO categories (category_name) VALUES (?)');
            $query->execute([$name]);
            echo '<div class="alert alert-success" role="alert">Your category has been added <a href="create.php?entity=project">Create new project.</a></div>';
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

    ##########################################################--- Project ---########################################################################


    public function createProject($name, $description, $goal, $category_name, $user_id) {
        $categoryquery = $this->db->prepare('SELECT category_ID FROM categories WHERE category_name = ?');
        $categoryquery->execute(array($category_name));
        $category_row = $categoryquery->fetch();
        $query = $this->db->prepare('INSERT INTO projects (project_name, project_description, number_of_backers, project_goal, category_ID, user_ID) VALUES (?, ?, ?, ?, ?, ?) ');
        $query->execute([$name, $description, 0, $goal, $category_row['category_ID'], $user_id]);
        $user_query = $this->db->query('SELECT projects_managed FROM users WHERE user_ID = '.$user_id);
        $user_row = $user_query->fetch();
        $user_row['projects_managed']++;
        $this->db->query('UPDATE users SET projects_managed = '.$user_row['projects_managed'].' WHERE user_ID = '.$user_id);
    }

    public function getProject($id){
        $projectQuery = $this->db->prepare('SELECT * FROM projects WHERE project_ID = ?');
        $projectQuery->execute(array($id));
        $projectArray = $projectQuery->fetch();
        return $projectArray;
    }

    public function deleteProject($project_id) {
        $query_rewards = $this->db->prepare('SELECT reward_ID FROM rewards WHERE project_ID = ?');
        $query_rewards->execute([$project_id]);
        while($query_rewards_fetch = $query_rewards->fetch()) {
            $this->deleteReward($query_rewards_fetch['reward_ID']);
        }
        $delete_project = $this->db->prepare('DELETE FROM projects WHERE project_ID = ?');
        $delete_project->execute([$project_id]);
    }

    public function projectName($project_id) {
        $result = $this->db->prepare('SELECT project_name FROM projects WHERE project_ID = ?'); 
        $result->execute([$project_id]);
        $projectName = $result->fetch();
        return $projectName;
    }

    public function modifyProject($name, $description, $goal, $project_id) {
        $modify_project = $this->db->prepare('UPDATE projects SET project_name = ?, project_description = ?, project_goal = ? WHERE project_ID = ?');
        $modify_project->execute([$name, $description, $goal, $project_id]);
    }

    public function getUsersSupportedProjects($userID){
        $newArray=[];
        $query = $this->db->prepare('SELECT project_ID FROM money_contributed WHERE user_ID = ?');
        $query->execute(array($userID));
        while($project = $query->fetch()){
            array_push($newArray, $project);
        }
        return $newArray;
    }

    ##########################################################--- Customer Adress ---########################################################################


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
        $address_query = $this->db->prepare('DELETE FROM customeraddresses WHERE user_ID = ?');
        $address_query->execute([$user_ID]);
    }

    public function modifyAddress($city, $state, $country, $zip, $user_ID) {
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

        ##########################################################--- Rewards ---########################################################################


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
        $delete_reward = $this->db->prepare('DELETE FROM rewards WHERE reward_ID = ?');
        $delete_reward->execute([$reward_id]);
    }

    public function rewardInfo($reward_id) {
        $reward_query = $this->db->prepare('SELECT * FROM rewards WHERE reward_ID = ?');
        $reward_query->execute([$reward_id]);
        return $reward_query->fetch();
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

        ##########################################################--- Orders ---########################################################################


    public function placeOrder($date_ordered, $date_fulfilled, $user_id) {
        $address_info = $this->addressInfo($user_id);
        $order_query = $this->db->prepare('INSERT INTO orders (date_ordered, date_fulfilled, user_ID, address_ID) VALUES (?, ?, ?, ?)');
        $order_query->execute([$date_ordered, $date_fulfilled, $user_id, $address_info['address_ID']]);
    }

    public function updateAmounts($backers, $money_collected, $project_id) {
        $amount_query = $this->db->prepare('UPDATE projects SET number_of_backers = ?, money_collected = ? WHERE project_ID = ?');
        $amount_query->execute([$backers, $money_collected, $project_id]);
    }


    public function addMoney($money_input, $project_id) {
        $project_query = $this->db->prepare('SELECT money_collected FROM projects WHERE project_ID = ?');
        $project_query->execute([$project_id]);
        $money_row = $project_query->fetch();
        $money_row['money_collected'] = $money_row['money_collected'] + $money_input;
        $new_money_query = $this->db->prepare('UPDATE projects SET money_collected = ? WHERE project_ID = ?');
        $new_money_query->execute([$money_row['money_collected'], $project_id]);
    }

    public function newContributor($money_contributed, $user_id, $project_id) {
        $user_query = $this->db->prepare('SELECT * FROM money_contributed WHERE user_ID = ? AND project_ID = ?');
        $user_query->execute([$user_id, $project_id]);
        $user_row = $user_query->fetch();
        if ($user_row) {
            $user_row['contributions'] = $user_row['contributions'] + $money_contributed;
            $update_user = $this->db->prepare('UPDATE money_contributed SET contributions = ? WHERE user_ID = ? AND project_ID = ?');
            $update_user->execute([$money_contributed, $user_id, $project_id]);
        }
        else {
            $update_user = $this->db->prepare('INSERT INTO money_contributed (contributions, user_ID, project_ID) VALUES (?, ?, ?)');
            $update_user->execute([$money_contributed, $user_id, $project_id]);
            $new_contributor = $this->db->prepare('SELECT number_of_backers FROM projects WHERE project_ID = ?');
            $new_contributor->execute([$project_id]);
            $contributor_row = $new_contributor->fetch();
            $contributor_row['number_of_backers'] = $contributor_row['number_of_backers'] + 1;
            $update_backers = $this->db->prepare('UPDATE projects SET number_of_backers = ? WHERE project_ID = ?');
            $update_backers->execute([$contributor_row['number_of_backers'], $project_id]);
            $support_query = $this->db->query('SELECT projects_supported FROM users WHERE user_ID = '.$user_id);
            $support_row = $support_query->fetch();
            $support_row['projects_supported'] = $support_row['projects_supported'] + 1;
            $support_query = $this->db->prepare('UPDATE users SET projects_supported = ? WHERE user_ID = ?');
            $support_query->execute([$support_row['projects_supported'], $user_id]);
        }
    }


    public function getUserContribution($userID, $projectID){
        if($userID == null){
            return null;
        }
        else{
        $query = $this->db->prepare('SELECT * FROM money_contributed WHERE user_ID = ? AND project_ID = ?');
        $query->execute(array($userID, $projectID));
        $array = $query->fetch();
        return $array;
        }
    }

     ##########################################################--- Utility Functions ---########################################################################


    public static function sortArray($array, $value){
        $newArray=[];
        while(sizeOf($array) > 0){
            $n=0;
            for($i=0;$i<count($array);$i++){
                if($array[$i][$value] < $array[$n][$value]){
                 $n = $i;
                }
            }
            array_push($newArray,$array[$n]);
            array_splice($array, $n, 1);
        }
        return $newArray;
    }

    

}
