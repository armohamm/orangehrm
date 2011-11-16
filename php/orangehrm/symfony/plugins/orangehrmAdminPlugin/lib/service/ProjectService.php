<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
class ProjectService extends BaseService {

	private $projectDao;

	/**
	 * Construct
	 */
	public function __construct() {
		$this->projectDao = new ProjectDao();
	}

	/**
	 *
	 * @return <type>
	 */
	public function getProjectDao() {
		return $this->projectDao;
	}

	/**
	 *
	 * @param UbCoursesDao $UbCoursesDao
	 */
	public function setProjectDao(ProjectDao $projectDao) {
		$this->projectDao = $projectDao;
	}

	/**
	 *
	 * @param ProjectDao $projectDao 
	 */
	public function setTimesheetDao(ProjectDao $projectDao) {

		$this->projectDao = $projectDao;
	}

	/**
	 * Get Project List
	 * 
	 * Get Active Project list in default. Can get all projects by passing $activeOnly as false.
	 * 
	 * @param type $noOfRecords
	 * @param type $offset
	 * @param type $sortField
	 * @param type $sortOrder
	 * @param type $activeOnly
	 * @return type 
	 */
	public function getProjectList($noOfRecords, $offset, $sortField, $sortOrder, $activeOnly) {
		return $this->projectDao->getProjectList($noOfRecords, $offset, $sortField, $sortOrder, $activeOnly);
	}

	/**
	 *
	 * get Project count
	 * 
	 * Get Active Project count in default. Can get all project count by passing $activeOnly as false.
	 * 
	 * @param type $activeOnly
	 * @return type 
	 */
	public function getProjectCount($activeOnly) {
		return $this->projectDao->getProjectCount($activeOnly);
	}

	/**
	 *
	 * Delete project
	 * 
	 * Set project's is_deleted flag to 1. This will handled the deleting of corresponding
	 * project activities and project admins under deleted project.
	 * 
	 * @param type $projectId
	 * @return type 
	 */
	public function deleteProject($projectId) {
		return $this->projectDao->deleteProject($projectId);
	}

	/**
	 *
	 * Delete project activity
	 * 
	 * Set project activity's is_deleted flag to 1.
	 * 
	 * @param type $activityId
	 * @return type 
	 */
	public function deleteProjectActivities($activityId) {
		return $this->projectDao->deleteProjectActivities($activityId);
	}

	/**
	 *
	 * Gret project by id.
	 * 
	 * @param type $projectId
	 * @return type 
	 */
	public function getProjectById($projectId) {
		return $this->projectDao->getProjectById($projectId);
	}

	/**
	 * 
	 * Get project activity by id.
	 * 
	 * @param type $projectId
	 * @return type 
	 */
	public function getProjectActivityById($projectId) {
		return $this->projectDao->getProjectActivityById($projectId);
	}

	/**
	 *
	 * Get all projects
	 * 
	 * Get all active projects as default. Can get all projects by passing $activeOnly parameter as false.
	 * 
	 * @return type 
	 */
	public function getAllProjects($activeOnly) {
		return $this->projectDao->getAllProjects($activeOnly);
	}

	/**
	 *
	 * Get active activity list for a project.
	 * 
	 * @param type $projectId
	 * @return type 
	 */
	public function getActivityListByProjectId($projectId) {
		return $this->projectDao->getActivityListByProjectId($projectId);
	}

	/**
	 * Will return wheather the activity has any timesheet records related.
	 * 
	 * @param type $activityId
	 * @return type 
	 */
	public function hasActivityGotTimesheetItems($activityId) {
		return $this->projectDao->hasActivityGotTimesheetItems($activityId);
	}

	/**
	 *
	 * Will return wheather the project has any timesheet records related.
	 * 
	 * @param type $projectId
	 * @return type 
	 */
	public function hasProjectGotTimesheetItems($projectId) {
		return $this->projectDao->hasProjectGotTimesheetItems($projectId);
	}

	/**
	 *
	 * Get active project list for a customer.
	 * 
	 * @param type $customerId
	 * @return type 
	 */
	public function getProjectsByCustomerId($customerId) {
		return $this->projectDao->getProjectsByCustomerId($customerId);
	}

	/**
	 * Get project list for login user
	 * 
	 * @param type $role
	 * @param type $empNumber
	 * @return type 
	 */
	public function getProjectListForUserRole($role, $empNumber) {
		return $this->projectDao->getProjectListForUserRole($role, $empNumber);
	}

	/**
	 * Gets project name with customer name given project id.
	 * 
	 * @param integer $projectId
	 * @return string
	 */
	public function getProjectNameWithCustomerName($projectId, $glue = " - ") {

		$project = $this->getProjectById($projectId);
		$projectName = $project->getCustomer()->getName() . $glue . $project->getName();

		return $projectName;
	}

	/**
	 * Get active project list
	 * 
	 * @return type 
	 */
	public function getActiveProjectList() {
		return $this->getProjectDao()->getActiveProjectList();
	}

	/**
	 * Get project list for a project admin
	 * 
	 * @param type $empNo
	 * @param type $emptyIfNotAprojectAdmin
	 * @return type 
	 */
	public function getProjectListByProjectAdmin($empNo, $emptyIfNotAprojectAdmin = false) {

		$projectAdmins = $this->getProjectDao()->getProjectAdminByEmpNumber($empNo);

		$projectIdArray = array();

		if (!is_null($projectAdmins)) {
			foreach ($projectAdmins as $projectAdmin) {
				$projectIdArray[] = $projectAdmin->getProjectId();
			}
		}

		if (empty($projectIdArray)) {
			return array();
		}

		$projectList = $this->getProjectDao()->getProjectsByProjectIds($projectIdArray);

		return $projectList;
	}

	/**
	 * Check wheather the user is a project admin
	 * 
	 * @param int $empNumber 
	 * @return boolean
	 */
	public function isProjectAdmin($empNumber) {
		$projects = $this->getProjectListByProjectAdmin($empNumber, true);
		return (count($projects) > 0);
	}

	/**
	 * 
	 * Search project by project name, customer name and project admin.
	 * 
	 * @param type $srchClues
	 * @param type $allowedProjectList
	 * @return type 
	 */
	public function searchProjects($srchClues, $allowedProjectList) {
		return $this->getProjectDao()->searchProjects($srchClues, $allowedProjectList);
	}

	/**
	 *
	 * Get project count of the search results.
	 * 
	 * @param type $srchClues
	 * @param type $allowedProjectList
	 * @return type 
	 */
	public function getSearchProjectListCount($srchClues, $allowedProjectList) {
		return $this->getProjectDao()->getSearchProjectListCount($srchClues, $allowedProjectList);
	}

}
