<?php

namespace App\Classes;

use App\Models\GeneralSetting;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class Openfire
{
    protected $params  = array();
    private $client;

    function __construct()
    {
        $this->client = new Client();
    }

    protected function doRequest($type, $endpoint, $params = [])
    {
        $url = GeneralSetting::getByKey('xmpp_url').$endpoint;
        $key = GeneralSetting::getByKey('xmpp_api_key');

        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => $key,
            'Content-Type' => 'application/json'
        );

        $body = json_encode($params);
        $logs = [
            'url' => $url,
            'method' => $type,
            'header' => json_encode($headers),
            'body'=>$body,
            'created_at'=>date('Y-m-d H:i:s'),
        ];

        try {
            $result = $this->client->request($type, $url, compact('headers', 'body'));
        } catch (\Exception $e) {
            $logs['response'] = $e->getMessage();
            $logs['updated_at'] =date('Y-m-d H:i:s');
            return  ['status' => false, 'data' => ['message' => $e->getMessage()],'logs'=>$logs];
        }

        $logs['response'] = $result->getBody();
        $logs['updated_at'] =date('Y-m-d H:i:s');
        DB::connection('mysql')->table('ext_log')->insert($logs);


        if ($result->getStatusCode() == 200 || $result->getStatusCode() == 201) {
            return array('status' => true, 'data' => json_decode($result->getBody()));
        }
        return array('status' => false, 'data' => json_decode($result->getBody()));
    }

    /**
     * Get all registered users
     *
     * @return array|false       Json with data or error, or False when something went fully wrong
     */
    public function getUsers($opts = [])
    {
        $query = '';

        if (isset($opts['search'])) $query .= '?search=' . $opts['search'];

        $endpoint = '/users' . $query;
        return $this->doRequest('GET', $endpoint);
    }


    /**
     * Get information for a specified user
     *
     * @return array|false       Json with data or error, or False when something went fully wrong
     */
    public function getUser($username)
    {
        $endpoint = '/users/' . $username;
        return $this->doRequest('GET', $endpoint);
    }


    /**
     * Creates a new OpenFire user
     *
     * @param   string          $username   Username
     * @param   string          $password   Password
     * @param   string|false    $name       Name    (Optional)
     * @param   string|false    $email      Email   (Optional)
     * @return  array|false                 Json with data or error, or False when something went fully wrong
     */
    public function addUser($username, $password, $name = false, $email = false)
    {
        $endpoint = '/users';
        return $this->doRequest('POST', $endpoint, compact('username', 'password', 'name', 'email'));
    }


    /**
     * Deletes an OpenFire user
     *
     * @param   string          $username   Username
     * @return  array|false                 Json with data or error, or False when something went fully wrong
     */
    public function deleteUser($username)
    {
        $endpoint = '/users/' . $username;
        return $this->doRequest('DELETE', $endpoint);
    }

    /**
     * Updates an OpenFire user
     *
     * @param   string          $username   Username
     * @param   string|false    $password   Password (Optional)
     * @param   string|false    $name       Name (Optional)
     * @param   string|false    $email      Email (Optional)
     * @param   string[]|false  $groups     Groups (Optional)
     * @return  array|false                 Json with data or error, or False when something went fully wrong
     */
    public function updateUser($username, $password, $name = false, $email = false, $groups = false)
    {
        $endpoint = '/users/' . $username;
        return $this->doRequest('PUT', $endpoint, compact('username', 'password', 'name', 'email'));
    }


    /**
     * Retrieve all user groups
     *
     * @param   string          $username   Username
     * @return  array|false                 Json with data or error, or False when something went fully wrong
     */
    public function userGroups($username)
    {
        $endpoint = '/users/' . $username . '/groups';
        return $this->doRequest('GET', $endpoint);
    }

    /**
     * Add user to groups
     *
     * @param   string          $username   Username
     * @param   array           $groups   Groups to add user
     * @return  array|false                 Json with data or error, or False when something went fully wrong
     */
    public function addToGroups($username, $groups)
    {
        $endpoint = '/users/' . $username . '/groups';
        return $this->doRequest('POST', $endpoint, $groups);
    }

    /**
     * Add user to a group
     *
     * @param   string          $username   Username
     * @param   string           $groups   Groups to add user
     * @return  array|false                 Json with data or error, or False when something went fully wrong
     */
    public function addToGroup($username, $groupName)
    {
        $endpoint = '/users/' . $username . '/groups/' . $groupName;
        return $this->doRequest('POST', $endpoint);
    }


    /**
     * Delete user from a group
     *
     * @param   string          $username   Username
     * @param   string           $groups   Groups to add user
     * @return  array|false                 Json with data or error, or False when something went fully wrong
     */
    public function deleteFromGroup($username, $groupName)
    {
        $endpoint = '/users/' . $username . '/groups/' . $groupName;
        return $this->doRequest('DELETE', $endpoint);
    }


    /**
     * lockout/Disable an OpenFire user
     *
     * @param   string          $username   Username
     * @return  array|false                 Json with data or error, or False when something went fully wrong
     */
    public function lockoutUser($username)
    {
        $endpoint = '/lockouts/' . $username;
        return $this->doRequest('POST', $endpoint);
    }


    /**
     * unlocks an OpenFire user
     *
     * @param   string          $username   Username
     * @return  array|false                 Json with data or error, or False when something went fully wrong
     */
    public function unlockUser($username)
    {
        $endpoint = '/lockouts/' . $username;
        return $this->doRequest('DELETE', $endpoint);
    }


    /**
     * List user rosters
     *
     * @param   string          $username           Username
     * @return  array|false                          Json with data or error, or False when something went fully wrong
     */
    public function userRosters($username)
    {
        $endpoint = '/users/' . $username . '/roster';
        return $this->doRequest('GET', $endpoint, compact('jid', 'name', 'subscriptionType'));
    }


    /**
     * Adds to this OpenFire user's roster
     *
     * @param   string          $username       	Username
     * @param   string          $jid            	JID
     * @param   string|false    $name           	Name         (Optional)
     * @param   int|false       $subscriptionType   	Subscription (Optional)
     * @return  array|false                     		Json with data or error, or False when something went fully wrong
     */
    public function addToRoster($username, $jid, $name = false, $subscriptionType = false)
    {
        $endpoint = '/users/' . $username . '/roster';
        return $this->doRequest('POST', $endpoint, compact('jid', 'name', 'subscriptionType'));
    }


    /**
     * Removes from this OpenFire user's roster
     *
     * @param   string          $username   Username
     * @param   string          $jid        JID
     * @return  array|false                 Json with data or error, or False when something went fully wrong
     */
    public function deleteFromRoster($username, $jid)
    {
        $endpoint = '/users/' . $username . '/roster/' . $jid;
        return $this->doRequest('DELETE', $endpoint, $jid);
    }

    /**
     * Updates this OpenFire user's roster
     *
     * @param   string          $username           Username
     * @param   string          $jid                 JID
     * @param   string|false    $nickname           Nick Name (Optional)
     * @param   int|false       $subscriptionType   Subscription (Optional)
     * @return  array|false                          Json with data or error, or False when something went fully wrong
     */
    public function updateRoster($username, $jid, $nickname = false, $subscriptionType = false)
    {
        $endpoint = '/users/' . $username . '/roster/' . $jid;
        return $this->doRequest('PUT', $endpoint, $jid, compact('jid', 'username', 'subscriptionType'));
    }




    /**
     * Gell all active sessions
     *
     * @return array|false       Json with data or error, or False when something went fully wrong
     */
    public function getChatRoom($name)
    {
        return $this->doRequest('GET', '/chatrooms/' . $name);
    }

    /**
     * Gell all chat rooms
     *
     * @return array|false       Json with data or error, or False when something went fully wrong
     */
    public function getAllChatRooms()
    {
        return $this->doRequest('GET', '/chatrooms?type=all');
    }


    /**
     * Create a chat room
     *
     * @param   string          $params        Params
     * @return  array|false                     Json with data or error, or False when something went fully wrong
     */
    public function createChatRoom($params = [])
    {
        return $this->doRequest('POST', '/chatrooms', $params);
    }


    /**
     * Update a chat room
     *
     * @param   string          $params        Params
     * @return  array|false                     Json with data or error, or False when something went fully wrong
     */
    public function updateChatRoom($roomName, $params = [])
    {
        return $this->doRequest('PUT', '/chatrooms/' . $roomName, $params);
    }


    /**
     * Delete a chat room
     *
     * @param   string      $name               Name of the Group to delete
     * @return  array|false                       Json with data or error, or False when something went fully wrong
     */
    public function deleteChatRoom($roomName)
    {
        return $this->doRequest('DELETE', '/chatrooms/' . $roomName);
    }

    /**
     * Get chat room participants
     *
     * @param   string      $name               Name of the chatroom
     * @return  array|false                       Json with data or error, or False when something went fully wrong
     */
    public function getChatRoomParticipants($roomName)
    {
        return $this->doRequest('GET', '/chatrooms/' . $roomName . '/participants');
    }

    /**
     * Get chat room occupants
     *
     * @param   string      $name               Name of the chatroom
     * @return  array|false                       Json with data or error, or False when something went fully wrong
     */
    public function getChatRoomOccupants($roomName)
    {
        return $this->doRequest('GET', '/chatrooms/' . $roomName . '/occupants');
    }

    /**
     * Add user with role to chatroom
     *
     * @param   string      $name               Name of the user or jid
     * @return  array|false                       Json with data or error, or False when something went fully wrong
     */
    public function addUserRoleToChatRoom($roomName, $name, $roles)
    {
        return $this->doRequest('POST', '/chatrooms/' . $roomName . '/' . $roles . '/' . $name);
    }


    /**
     * Add group with role to chatroom
     *
     * @param   string      $name               Name of the group
     * @return  array|false                       Json with data or error, or False when something went fully wrong
     */
    public function addGroupRoleToChatRoom($roomName, $name, $roles)
    {
        return $this->doRequest('POST', '/chatrooms/' . $roomName . '/' . $roles . '/group/' . $name);
    }


    /**
     * Delete a user from a chat room
     *
     * @param   string      $name               Name of the group
     * @return  array|false                       Json with data or error, or False when something went fully wrong
     */
    public function deleteChatRoomUser($roomName, $name, $roles)
    {
        return $this->doRequest('DELETE', '/chatrooms/' . $roomName . '/' . $roles . '/' . $name);
    }


    /**
     * Retrieve all system properties
     *
     * @return  array|false                       Json with data or error, or False when something went fully wrong
     */
    public function getSystemProperties()
    {
        return $this->doRequest('GET', '/system/properties');
    }

    /**
     * Retrieve a system property
     *
     * @param   string      $name                Name of property
     * @return  array|false                       Json with data or error, or False when something went fully wrong
     */
    public function getSystemProperty($propertyName)
    {
        return $this->doRequest('GET', '/system/properties/' . $propertyName);
    }


    /**
     * Create a system property
     *
     * @param   array      $data                new property with value
     * @return  array|false                       Json with data or error, or False when something went fully wrong
     */
    public function createSystemProperty($data)
    {
        return $this->doRequest('POST', '/system/properties', $data);
    }


    /**
     * Update a system property
     *
     * @param   string     $propertyName        name of property to update
     * @param   array      $data                new property with value
     * @return  array|false                       Json with data or error, or False when something went fully wrong
     */
    public function updateSystemProperty($propertyName, $data)
    {
        return $this->doRequest('POST', '/system/properties/' . $propertyName, $data);
    }


    /**
     * Delete a system property
     *
     * @param   array      $data                new property with value
     * @return  array|false                       Json with data or error, or False when something went fully wrong
     */
    public function deleteSystemProperty($propertyName)
    {
        return $this->doRequest('DELETE', '/system/properties/' . $propertyName);
    }


    /**
     * Retrieve concurrent sessions
     *
     * @return  array|false                       Json with data or error, or False when something went fully wrong
     */
    public function getConcurrentSessons()
    {
        return $this->doRequest('GET', '/system/statistics/sessions');
    }


    /**
     * Get all groups
     *
     * @return  array|false      Json with data or error, or False when something went fully wrong
     */
    public function getGroups()
    {
        $endpoint = '/groups';
        return $this->doRequest('GET', $endpoint);
    }

    /**
     *  Retrieve a group
     *
     * @param  string   $name                       Name of group
     * @return  array|false                          Json with data or error, or False when something went fully wrong
     */
    public function getGroup($name)
    {
        $endpoint = '/groups/' . $name;
        return $this->doRequest('GET', $endpoint);
    }

    /**
     * Create a group 
     *
     * @param   string   $name                      Name of the group
     * @param   string   $description               Some description of the group
     *
     * @return  array|false                          Json with data or error, or False when something went fully wrong
     */
    public function createGroup($name, $description = false)
    {
        $endpoint = '/groups/';
        return $this->doRequest('POST', $endpoint, compact('name', 'description'));
    }

    /**
     * Delete a group
     *
     * @param   string      $name               Name of the Group to delete
     * @return  array|false                          Json with data or error, or False when something went fully wrong
     */
    public function deleteGroup($name)
    {
        $endpoint = '/groups/' . $name;
        return $this->doRequest('DELETE', $endpoint);
    }

    /**
     * Update a group (description)
     *
     * @param   string      $name               Name of group
     * @param   string      $description        Some description of the group
     *
     */
    public function updateGroup($name,  $description)
    {
        $endpoint = '/groups/' . $name;
        return $this->doRequest('PUT', $endpoint, compact('name', 'description'));
    }


    /**
     * Retrieve all sessions
     *
     * @return array|false       Json with data or error, or False when something went fully wrong
     */
    public function getSessions()
    {
        $endpoint = '/sessions';
        return $this->doRequest('GET', $endpoint);
    }

    /**
     * Retrieve all user sessions
     *
     * @param   string      $username               Username of user
     * @return array|false       Json with data or error, or False when something went fully wrong
     */
    public function getUserSessions($username)
    {
        $endpoint = '/sessions/' . $username;
        return $this->doRequest('GET', $endpoint);
    }


    /**
     * Close all user sessions
     * @param string      $username               Username of user 
     * @return array|false       Json with data or error, or False when something went fully wrong
     */
    public function closeUserSessions($username)
    {
        $endpoint = '/sessions/' . $username;
        return $this->doRequest('DELETE', $endpoint);
    }

    /**
     * Send a broadcast message to all online users
     *
     * @param  string      $content               message to send
     * @return array|false       Json with data or error, or False when something went fully wrong
     */
    public function broadcastMessage($message = '')
    {
        $content = ['body' => $message];
        $endpoint = '/messages/users';
        return $this->doRequest('POST', $endpoint, $content);
    }
}
