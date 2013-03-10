<?php
/**
 * ownCloud - Mail app
 *
 * @author Sebastian Schmid
 * @copyright 2013 Sebastian Schmid mail@sebastian-schmid.de
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace \OCA\mail\Db;

use \OCA\AppFramework\Db\DoesNotExistException;
use \OCA\AppFramework\Db\Mapper;

class MailAccountMapper extends Mapper {

	private $tablename;
	
	/**
	 * @param API @api Instance of the API abstraction layer
	 */
	public function __construct($api){
		parent::__construct($api);
		$this->tableName = '*PREFIX*mail_mailaccounts';
	}
	
	/** Finds an Mail Account by id
	 * @throws DoesNotExistException if no Mail Account exists
	 * @return Mail Account
	 */
	public function find($mailAccountId){
		 $row = $this->findQuery($this->tableName, $mailAccountId);
		 return new MailAccount($row);
	 }
	
	/**
	 * Finds all Mail Account by user id existing for this user
	 * @param string $ocUserId the id of the user that we want to find
	 * @throws DoesNotExistException if no Mail Account exists
	 * @return all Mail Accounts for that User as an Array
	 */
	public function findByUserId($ocUserId)
		$sql = 'SELECT * FROM ' . $this->tableName . ' WHERE userid = ?';
		$params = array($ocUserId);
		
		$results = $this->execute($sql, $params)->fetchRow();
		if($results){
			foreach ($results as $result){
				$mailAccount = new MailAccount($result);
				$mailAccounts[] = $mailAccount;
			}
			return $mailAccounts;
		}else{
			throw new DoesNotExistException('There are no Mail Accounts configured for user id ' . $ocUserId);
		}
	}
	
	/**
	 * Saves an User Account into the database
	 * @param User Account $userAccount the User Account to be saved
	 * @return User Account the User Account with the filled in mailaccountid
	 */
	public function save($mailAccount){
		 $sql = 'INSERT INTO ' . $this->tableName . '(email, inboundHost, inboundHostPort, etc)' . 
		 	'VALUES(?, ?, ?)';
		 
		 $params = array(
			 $mailAccount->getEmail(),
			 $mailAccount->getInboundHost(),
			 $mailAccount->getInboundHostPort()
		 );
		 
		 $this->execute($sql, $params);
		 
		 $mailAccount->setId($this->api->getInsertId());
		 
		 return $mailAccount;
	 }
	 
	 /**
	  * Updates a Mail Account
	  * @param Mail Account $mailAccount: the Mail Account to be updated
	  */
	 public function update($mailAccount){
		 $sql = 'UPDATE ' . $this->tableName . 'SET
		 	email = ?,
			inboundHost = ?
			WHERE mailaccountid = ?';
			
		$params = array(
			$mailaccount->getEmail(),
			$mailaccount->getInboundHost()
		);
		
		$this->execute($sql, $params);
	 }
	 
	 /**
	  *
	  *
	  */
	public function delete($mailAccountId){
		$this->deleteQuery($this->tableName, $mailaccountid);
	}
	
}