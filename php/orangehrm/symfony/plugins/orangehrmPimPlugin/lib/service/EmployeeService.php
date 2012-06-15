<?php

/*
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

/**
 * EmployeeService class file
 */

/**
 * Employee Service
 * @package pim
 * @todo Remove exceptions that only wraps DAO exceptions [DONE]
 * @todo Add get/save/delete for all 
 * @todo Add deleteReportingMethod() function
 * @todo Add getEmployeeImmigrationRecords method
 * @todo Add getEmployeeChildren method
 * @todo All methods to return PIMServiceException or DaoException consistantly [DONE]
 * @todo Don't wrap DAO exceptions. [DONE]
 * @todo Deside if all methods need to have try catch blocks [DONE]
 * @todo Show class hierarchy (inheritance) of all the classes in the API
 */
class EmployeeService extends BaseService {

    /**
     * @ignore
     */
    private $employeeDao;

    /**
     * Get Employee Dao
     * @return EmployeeDao
     * @ignore
     */
    public function getEmployeeDao() {
        return $this->employeeDao;
    }

    /**
     * Set Employee Dao
     * @param EmployeeDao $employeeDao
     * @return void
     * @ignore
     */
    public function setEmployeeDao(EmployeeDao $employeeDao) {
        $this->employeeDao = $employeeDao;
    }

    /**
     * Construct
     * @ignore
     */
    public function __construct() {
        $this->employeeDao = new EmployeeDao();
    }

    /**
     * Save an employee
     * 
     * If empNumber is not set, it will be set to next available value and a 
     * new employee will be added.
     * 
     * If empNumber is set, and it belongs to an existing employee, the employee
     * is updated.
     * 
     * If empNumber is set and it does not belong to an existing employee, a 
     * new employee is added. The caller has to update the unique id using 
     * IDGeneratorService.
     * 
     * @version 2.6.11
     * @param Employee $employee
     * @return boolean
     * @throws PIMServiceException
     * 
     * @todo Return Saved Employee [DONE]
     * @todo Change method name to saveEmployee [DONE]
     */
    public function saveEmployee(Employee $employee) {
        return $this->getEmployeeDao()->saveEmployee($employee);
    }

    /**
     * Get employee for given empNumber
     * 
     * @version 2.6.11
     * @param int $empNumber Employee number
     * @return Employee Employee instance if found or NULL
     * @throws PIMServiceException
     */
    public function getEmployee($empNumber) {
        return $this->getEmployeeDao()->getEmployee($empNumber);
    }

    /**
     * Get an employee by employee ID
     *
     * @version 2.6.12.1
     * @param string $employeeId Employee ID
     * @return Employee Employee instance if found or false
     * @todo return null if not found (instead of returning false)
     */
    public function getEmployeeByEmployeeId($employeeId) {
        return $this->getEmployeeDao()->getEmployeeByEmployeeId($employeeId);
    }

    /**
     * Get the default employee id to be used for next employee being
     * added to the system.
     * 
     * @return employee id based on empNumber
     * 
     * @ignore
     */
    public function getDefaultEmployeeId() {
        $idGenService = new IDGeneratorService();
        $idGenService->setEntity(new Employee());
        return $idGenService->getNextID(false);
    }

    /**
     * Retrieve picture for given employee number
     * 
     * @version 2.6.11
     * @param int $empNumber
     * @return EmpPicture EmpPicture or null if no picture found 
     * @throws PIMServiceException
     * 
     * @todo Rename to getEmployeePicture [DONE]
     */
    public function getEmployeePicture($empNumber) {
        return $this->getEmployeeDao()->getEmployeePicture($empNumber);
    }

    /**
     * Save Personal Details of given employee
     * 
     * @param Employee $employee
     * @param boolean $isESS
     * @return boolean true always
     * @throws PIMServiceException
     * 
     * @todo Don't return value
     * @todo Remove $isESS parameter and handle it in action
     */
    public function savePersonalDetails(Employee $employee, $isESS = false) {
        return $this->getEmployeeDao()->savePersonalDetails($employee, $isESS);
    }

    /**
     * Save Employee Contact Details of given employee
     * 
     * @version 2.6.11
     * @param Employee $employee
     * @return boolean
     * @throws PIMServiceException
     * 
     * @todo Don't return any value (currently returns true always)
     * @todo Exceptions should preserve previous exception [DONE]
     */
    public function saveContactDetails(Employee $employee) {
        return $this->getEmployeeDao()->saveContactDetails($employee);
    }

    /**
     * Get Emergency contacts for given employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @return array EmpEmergencyContact objects as array. Array will be empty 
     *               if no emergency contacts defined fo
     * r employee.
     * @throws PIMServiceException
     * 
     * @todo Rename method as getEmployeeEmergencyContacts [DONE]
     */
    public function getEmployeeEmergencyContacts($empNumber) {
        return $this->getEmployeeDao()->getEmployeeEmergencyContacts($empNumber);
    }

    /**
     * Delete the given emergency contacts from the given employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param array $emergencyContactsToDelete Array of emergency contact seqNo values.
     * @returns boolean
     * @throws PIMServiceException
     * 
     * @todo return number of contacts deleted (currently returns true always)
     * @todo Exceptions should preserve previous exception [DONE]
     * @todo rename method as deleteEmployeeEmergencyContacts [DONE]
     */
    public function deleteEmployeeEmergencyContacts($empNumber, $emergencyContactsToDelete) {
        return $this->getEmployeeDao()->deleteEmployeeEmergencyContacts($empNumber, $emergencyContactsToDelete);
    }

    /**
     * Delete the given immigration entries for the given employee.
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param array $entriesToDelete Array of immigration entry seqno values.
     * @returns boolean
     * @throws PIMServiceException
     * 
     * @todo Rename to deleteEmployeeImmigrationRecords [DONE]
     * @todo return number of entries deleted (currently returns true always)
     */
    public function deleteEmployeeImmigrationRecords($empNumber, $entriesToDelete) {
        return $this->getEmployeeDao()->deleteEmployeeImmigrationRecords($empNumber, $entriesToDelete);
    }

    /**
     * Get dependents for given employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @return array EmpDependent Array of EmpDependent objects
     * 
     * @todo Exceptions should preserve previous exception [DONE]
     * @todo Rename method as getEmployeeDependents [DONE]
     */
    public function getEmployeeDependents($empNumber) {
        return $this->getEmployeeDao()->getEmployeeDependents($empNumber);
    }

    /**
     * Delete the given dependents from the given employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param array $entriesToDelete Array of dependent seqno values.
     * @returns boolean
     * @throws PIMServiceException
     * 
     * @todo return number of entries deleted (currently returns true always)
     * @todo Exceptions should preserve previous exception [DONE]
     * @todo Rename method as deleteEmployeeDependents [DONE]
     */
    public function deleteEmployeeDependents($empNumber, $entriesToDelete) {
        return $this->getEmployeeDao()->deleteEmployeeDependents($empNumber, $entriesToDelete);
    }

    /**
     * Delete given children from the given employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param array $entriesToDelete Array of EmpChild seqno values 
     * @returns boolean
     * @throws PIMServiceException
     * 
     * @todo return number of entries deleted (currently returns true always)
     * @todo Exceptions should preserve previous exception [DONE]
     * @todo rename method as deleteEmployeeChildren [DONE]
     */
    public function deleteEmployeeChildren($empNumber, $entriesToDelete) {
        return $this->getEmployeeDao()->deleteEmployeeChildren($empNumber, $entriesToDelete);
    }

    /**
     * Check if employee with given employee number is a supervisor
     * @ignore
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @return bool True if given employee is a supervisor, false if not
     * 
     * @todo Exceptions should preserve previous exception [DONE]
     */
    public function isSupervisor($empNumber) {
        return $this->getEmployeeDao()->isSupervisor($empNumber);
    }

    /**
     * Delete picture of the given employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @return boolean
     * @throws PIMServiceException
     * 
     * @todo Don't return any value (currently returns true always)
     * @todo Exceptions should preserve previous exception [DONE]
     * @todo Rename to deleteEmployeePicture (to match with get method) [DONE]
     */
    public function deleteEmployeePicture($empNumber) {
        return $this->getEmployeeDao()->deleteEmployeePicture($empNumber);
    }

    /**
     * Save the given employee picture.
     * 
     * @version 2.6.11
     * @param EmpPicture $empPicture EmpPicture object to save
     * @return boolean
     * @throws PIMServiceException
     * 
     * @todo Return saved EmpPicture 
     * @todo Exceptions should preserve previous exception [DONE]
     * @todo Rename to savePicture (without Employee) to match other methods
     */
    public function saveEmployeePicture(EmpPicture $empPicture) {
        return $this->getEmployeeDao()->saveEmployeePicture($empPicture);
    }

    /**
     * Save given employee immigration entry
     * 
     * @version 2.6.11
     * @param EmpPassport $empPassport EmpPassport instance
     * @return boolean
     * 
     * @todo Rename to saveEmployeeImmigrationEntry (without Employee) and change Passport -> Immigration
     * @todo Rename EmpPassport to EmpImmigrationRecord
     * @todo return saved EmpImmigrationRecord
     */
    public function saveEmployeePassport(EmpPassport $empPassport) {
        return $this->getEmployeeDao()->saveEmployeePassport($empPassport);
    }

    /**
     * Get Employee Immigration Record(s) for given employee.
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param int $recordId Immigration Record sequence Number (optional)
     * 
     * @return Doctrine_Collection/EmpPassport If sequenceNo is given returns matching 
     * Immigration Record or false if not found. If sequenceNo is not given, returns Immigration 
     * Record collection. (Empty collection if no records available)
     * 
     * @todo rename to getEmployeeImmigrationRecords [DONE]
     * @todo rename $sequenceNo to a meaningful values. Ex: $recordId [DONE]
     */
    public function getEmployeeImmigrationRecords($empNumber, $recordId = null) {
        return $this->getEmployeeDao()->getEmployeeImmigrationRecords($empNumber, $recordId);
    }

    /**
     * Save given Work Experience Record
     * 
     * @version 2.6.11
     * @param EmpWorkExperience $empWorkExp Work Experience record to save
     * @return boolean
     * @throws DaoException
     * 
     * @todo return saved work Experience
     * @todo rename method as saveEmployeeWorkExperience [DONE]
     */
    public function saveEmployeeWorkExperience(EmpWorkExperience $empWorkExp) {
        return $this->getEmployeeDao()->saveEmployeeWorkExperience($empWorkExp);
    }

    /**
     * Get Work Experience Record(s) for given employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee number
     * @param int $recordId Work Experience record sequence number
     * 
     * @return Doctrine_Collection/WorkExperience  If sequenceNo is given returns matching 
     * EmpWorkExperience or false if not found. If sequenceNo is not given, returns 
     * EmpWorkExperience collection. (Empty collection if no records available)
     * @throws DaoException
     * 
     * @todo Rename method as getEmployeeWorkExperienceRecords [DONE]
     */
    public function getEmployeeWorkExperienceRecords($empNumber, $recordId = null) {
        return $this->getEmployeeDao()->getEmployeeWorkExperienceRecords($empNumber, $recordId);
    }

    /**
     * Delete given WorkExperience entries from given employee.
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param array $workExperienceToDelete sequenceNos of the work experience
     *              records to delete
     * 
     * @return boolean True if workExperienceToDelete is not empty, false if empty.
     * @throws DaoException
     * 
     * @todo return number of entries deleted
     * @todo rename method as deleteEmployeeWorkExperienceRecords [DONE]
     */
    public function deleteEmployeeWorkExperienceRecords($empNumber, $workExperienceToDelete) {
        return $this->getEmployeeDao()->deleteEmployeeWorkExperienceRecords($empNumber, $workExperienceToDelete);
    }

    /**
     * Get Employee Education with given id
     * 
     * @ignore
     * 
     * @version 2.6.11
     * @param int $id Education Id
     * @return EmployeeEducation If Id match with records return EmployeeEducation else return false
     * @throws DaoException
     * 
     * @todo Rename method as getEmployeeEducation 
     */
    public function getEducation($id) {
        return $this->getEmployeeDao()->getEducation($id);
    }
    
    /**
     * Get Education Record(s) for given employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee number
     * @param int $educationId Education record id
     * 
     * @return Collection/Education If education id is given returns matching 
     * EmpEducation or false if not found. If educationId is not given, returns 
     * EmpEducation collection. (Empty collection if no records available)
     * 
     * @todo rename method as getEmployeeEducations
     * @todo If EducationId is given return EmployeeEducation instead of Doctrine_Collection
     */
    public function getEmployeeEducationList($empNumber, $educationId=null) {
        return $this->getEmployeeDao()->getEmployeeEducationList($empNumber, $educationId);
    }   

    /**
     * Delete given education entries for given employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param array $educationToDelete sequenceNos of the education entries to delete
     * @return boolean True if educationToDelete is not empty, false if empty.
     * @throws DaoException
     * 
     * @todo return number of entries deleted (currently return value is based on $educationToDelete not actual deleted records)
     * @todo rename method as deleteEmployeeEducationRecords [DONE]
     */
    public function deleteEmployeeEducationRecords($empNumber, $educationToDelete) {
        return $this->getEmployeeDao()->deleteEmployeeEducationRecords($empNumber, $educationToDelete);
    }

    /**
     * Save the given EmployeeEducation entry.
     * 
     * @version 2.6.11
     * @param EmployeeEducation $education EmployeeEducation object to save
     * @returns boolean true
     * @throws DaoException
     * 
     * @todo return saved Employee Education object
     * @todo rename method as saveEmployeeEducation [DONE]
     */
    public function saveEmployeeEducation(EmployeeEducation $education) {
        return $this->getEmployeeDao()->saveEmployeeEducation($education);
    }

    /**
     * Get all skills or a single skill with given skill code for given employee.
     * 
     * If skillCode is null, returns all Doctrine_Collection/EmployeeSkill objects for the employee.
     * If skillCode is given, returns the Doctrine_Collection/EmployeeSkill object with given skillcode.
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param int $skillCode Skill Code
     * @returns Doctrine_Collection/EmployeeSkill 
     * 
     * @todo rename method as getEmployeeSkills [DONE]
     * 
     */
    public function getEmployeeSkills($empNumber, $skillCode = null) {
        return $this->getEmployeeDao()->getEmployeeSkills($empNumber, $skillCode);
    }

    /**
     * Delete given skill entries for given employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param array $skillToDelete ids of the skill entries to delete
     * @return boolean True if $skillToDelete is not empty, false if empty.
     * @throws DaoException
     * 
     * @todo return number of entries deleted
     * @todo rename method as deleteEmployeeSkills [DONE]
     */
    public function deleteEmployeeSkills($empNumber, $skillToDelete) {
        return $this->getEmployeeDao()->deleteEmployeeSkills($empNumber, $skillToDelete);
    }

    /**
     * Save the given EmployeeSkill entry.
     * 
     * @version 2.6.11
     * @param EmployeeSkill $skill EmployeeSkill object to save
     * @returns boolean true
     * 
     * @todo reurn saved Employee Skill object
     * @todo rename method as saveEmployeeSkill [DONE]
     */
    public function saveEmployeeSkill(EmployeeSkill $skill) {
        return $this->getEmployeeDao()->saveEmployeeSkill($skill);
    }

    /** 
     * Retrieve Employee Language for given employee number 
     * 
     * If language code is not set, It returns all languages for Employee
     * 
     * If Language Type is not set, It returns all languages for Employee
     * 
     * If Language code and Language type are set, It returns a Language for Employee  
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param String $languageCode Language Code 
     * @param String $languageType Language Type
     * @return Doctrine_Collection/Array Returns Doctrine_Collection of EmployeeLanguage objects  or EmployeeLanguage object
     * 
     * @todo rename method as getEmployeeLanguages [DONE]
     * 
     */
    public function getEmployeeLanguages($empNumber, $languageCode = null, $languageType = null) {
        return $this->getEmployeeDao()->getEmployeeLanguages($empNumber, $languageCode, $languageType);
    }

    /** 
     * Deletes languages assigned to an employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param array() $languagesToDelete Associative array of with language IDs as keys and fluency types as values
     * @return int Number of records deleted. False if $languagesToDelete is empty
     * 
     * @todo return number of entries deleted
     * @todo rename method as deleteEmployeeLanguages [DONE]
     */
    public function deleteEmployeeLanguages($empNumber, $languagesToDelete) {
        return $this->getEmployeeDao()->deleteEmployeeLanguages($empNumber, $languagesToDelete);
    }

    /**
     * Save given Employee Language entry
     * 
     * @version 2.6.11
     * @param EmployeeLanguage $language Employee Language
     * @returns boolean 
     * 
     * @todo return saved Employee Language entry
     * @todo rename method as saveEmployeeLanguage [DONE]
     * 
     */
    public function saveEmployeeLanguage(EmployeeLanguage $language) {
        return $this->getEmployeeDao()->saveEmployeeLanguage($language);
    }

    /**
     * Retrieves license(s) of an employee
     * 
     * If license ID is not set, It returns all licenses of employee
     * 
     * If licence ID is set, It returns an EmployeeLicense object
     *  
     * @version 2.6.11
     * @param int $empNumber 
     * @param int $licenseId
     * @returns Doctrine_Collection/License Returns Doctrine_Collection of EmployeeLicense objects or single object
     * 
     * @todo rename method as getEmployeeLicences [DONE]
     * 
     */
    public function getEmployeeLicences($empNumber, $licenseId = null) {
        return $this->getEmployeeDao()->getEmployeeLicences($empNumber, $licenseId);
    }

    /**
     * Deletes license of an employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee number
     * @param array $licenseToDelete Array of license IDs
     * @return boolean False if $licenseToDelete is empty or true otherwise
     * 
     * @todo Return number of items deleted
     * @todo Rename method as deleteEmployeeLicenses [DONE]
     * 
     */
    public function deleteEmployeeLicenses($empNumber, $licenseToDelete) {
        return $this->getEmployeeDao()->deleteEmployeeLicenses($empNumber, $licenseToDelete);
    }


    /**
     * Assign a license or update an assigned license of an employee
     * 
     * @version 2.6.11
     * @param EmployeeLicense $license Populated EmployeeLicense object
     * @return boolean True always
     * 
     * @todo return saved Employee License entry
     * @todo rename method as saveEmployeeLicense [DONE]
     * 
     */    
    public function saveEmployeeLicense(EmployeeLicense $license) {
        return $this->getEmployeeDao()->saveEmployeeLicense($license);
    }

    /**
     * Get attachments of an employee for given screen
     * 
     * @version 2.6.11
     * @param int $empNumber Employee number
     * @param string $screen Screen name
     * 
     * @return Doctrine_Collection Doctrine_Collection of EmployeeAttachment objects
     * 
     * @todo Define screen name constant in PluginEmployeeAttachment class 
     * @todo rename method as getEmployeeAttachments [DONE]
     * @todo Define the values for $screen as constants use constants names here
     */
    public function getEmployeeAttachments($empNumber, $screen) {
        return $this->getEmployeeDao()->getEmployeeAttachments($empNumber, $screen);
    }

    /**
     * Deletes attachments of an employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee number
     * @param array $attachmentsToDelete Array of attachement IDs
     * @return boolean False if $attachmentsToDelete is empty or true otherwise
     * 
     * @todo rename method as deleteEmployeeAttachments [DONE]
     * @todo return number of items deleted
     */
    public function deleteEmployeeAttachments($empNumber, $attachmentsToDelete) {
        return $this->getEmployeeDao()->deleteEmployeeAttachments($empNumber, $attachmentsToDelete);
    }

    /**
     * Retrieves an attachment of an employee 
     * 
     * @version 2.6.11
     * @param int $empNumber Employee number 
     * @param int $attachmentId Attachment ID
     * 
     * @return EmployeeAttachment/boolean If no records return false 
     * 
     * @todo rename method as getEmployeeAttachment [DONE]
     */
    public function getEmployeeAttachment($empNumber, $attachmentId) {
        return $this->getEmployeeDao()->getEmployeeAttachment($empNumber, $attachmentId);
    }

    /**
     * Retrieve Employee Picture for an employee
     * 
     * @ignore
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @returns EmpPicture Employee Picture object
     * 
     * @throws PIMServiceException
     * 
     * @todo remove method and use getEmployeePicture
     */
    public function readEmployeePicture($empNumber) {
        return $this->getEmployeeDao()->readEmployeePicture($empNumber);
    }

    /**
     * Retrieve Employee list according to terminated status
     * 
     * @version 2.6.11
     * @param String $orderField Order Field, default is empNumber
     * @param String $orderBy Order By, Default is ASC
     * @param boolean $includeTerminatedEmployees 
     * @return Doctrine_Collection/Array Returns Doctrine_Collection of Employee objects
     * @throws PIMServiceException
     * 
     * @todo Change default $orderField to last name
     */
    public function getEmployeeList($orderField = 'empNumber', $orderBy = 'ASC', $includeTerminatedEmployees = false) {
        return $this->getEmployeeDao()->getEmployeeList($orderField, $orderBy, $includeTerminatedEmployees);
    }
    
    /**
     * Returns employee IDs of all the employees in the system
     * 
     * @version 2.7.1
     * @return Array List of employee IDs
     */
    public function getEmployeeIdList() {
        return $this->getEmployeeDao()->getEmployeeIdList();
    }
    
    /**
     * Returns an array of requested properties of all employees
     * 
     * <pre>
     * Ex: $properties = array('empNumber', 'firstName', 'lastName')
     * 
     * For above $properties parameter there will be an array like below as the response.
     * 
     * array(
     *          0 => array('empNumber' => 1, 'firstName' => 'Kayla', 'lastName' => 'Abbey'),
     *          1 => array('empNumber' => 1, 'firstName' => 'Ashley', 'lastName' => 'Abel')
     * )
     * </pre>
     * 
     * @version 2.7.1
     * @param $properties An array of strings containing names of required properties
     * @param $orderField Field to be used for ordering
     * @param $orderBy ASC or DESC
     * @return Array Employee Property List 
     */
    public function getEmployeePropertyList($properties, $orderField, $orderBy) {
        return $this->getEmployeeDao()->getEmployeePropertyList($properties, $orderField, $orderBy);
    }
    

    /**
     * Returns list of supervisors (employees having at least one subordinate)
     *
     * @version 2.6.11
     * @returns Doctrine_Collection/Array Returns Doctrine_Collection of Employee objects
     * @throws PIMServiceException
     * 
     * @todo add orderField,oraderBy and include Deleted parameters
     */
    public function getSupervisorList() {
        return $this->getEmployeeDao()->getSupervisorList();
    }

    /**
     * Search Employee for given field and value 
     * 
     * @ignore
     * 
     * @version 2.6.11
     * @param String $field property name
     * @param String $value property value 
     * @return Doctrine_Collection/Array Returns Doctrine_Collection of Employee objects
     * @throws PIMServiceException
     */
    public function searchEmployee($field, $value) {
        return $this->getEmployeeDao()->searchEmployee($field, $value);
    }

    /**
     * Returns Employee Count according to terminated status
     * 
     * @version 2.6.11
     * @param boolean $withoutTerminatedEmployees 
     * @returns int Employee Count
     * 
     * @throws PIMServiceException
     * 
     * @todo Change parameter to include terminated and change logic 
     */
    public function getEmployeeCount($withoutTerminatedEmployees = false) {
        return $this->getEmployeeDao()->getEmployeeCount($withoutTerminatedEmployees = false);
    }

    /**
     * Get Immediate subordinates of the given employee.
     * 
     * @version 2.6.11
     * @param int $empNumber Supervisor Id
     * @returns Doctrine_Collection/Array Returns Doctrine_Collection of Employee objects
     * @throws PIMServiceException
     * 
     * @todo Rename to getImmediateSubordinates($empNumber) [DONE]
     * @todo improve DAO method performance , currently it execute few queries 
     * 
     */
    public function getImmediateSubordinates($empNumber) {
        return $this->getEmployeeDao()->getImmediateSubordinates($empNumber);
    }

    /**
     * @ignore
     * 
     * Returns Employee List as Json string 
     * 
     * if workShift parameter is true json string include employee work shift value 
     * 
     * @version 2.6.11
     * @param boolean $workShift Work Shift
     * @returns String Json string include employee name and employee id
     * 
     * @throws PIMServiceException
     * 
     * @todo Remove WorkShift Parameter , currently it's not used in DAO method 
     * @todo Create Json string in service method instead of DAO method. DAO can
     * return array of name and id values.
     * @todo Improve performance of dao method
     */
    public function getEmployeeListAsJson($workShift = false) {
        return $this->getEmployeeDao()->getEmployeeListAsJson($workShift);
    }

    /**
     * Retrieve Supervisor Employee Chain 
     * 
     * @version 2.6.11
     * @param int $supervisorId Supervisor Id
     * @param boolean $withoutTerminatedEmployees Terminated status
     * @throws PIMServiceException 
     * 
     * @todo parameter name $withoutTerminatedEmployees does not give the correct meaning
     * @todo rename method as getSubordinateChain($empNumber , $includeTerminated )
     * @todo rename second parameter as include Terminated as change DAO method logic
     */
    public function getSupervisorEmployeeChain($supervisorId, $withoutTerminatedEmployees = false) {
        return $this->getEmployeeDao()->getSupervisorEmployeeChain($supervisorId, $withoutTerminatedEmployees);
    }
    
    /**
     * Returns an array of employee IDs of subordinates for given supervisor ID
     * 
     * IDs of whole chain under given supervisor are returned.
     * 
     * @version 2.7.1
     * @param int $supervisorId Supervisor's ID
     * @return Array An array of employee IDs
     */
    public function getSubordinateIdListBySupervisorId($supervisorId) {
        return $this->getEmployeeDao()->getSubordinateIdListBySupervisorId($supervisorId);
    }
    
    /**
     * Returns an array of requested properties of subordinates of given supervisor ID
     * 
     * <pre>
     * Ex: $properties = array('empNumber', 'firstName', 'lastName')
     * 
     * For above $properties parameter there will be an array like below as the response.
     * 
     * array(
     *          0 => array('empNumber' => 1, 'firstName' => 'Kayla', 'lastName' => 'Abbey'),
     *          1 => array('empNumber' => 1, 'firstName' => 'Ashley', 'lastName' => 'Abel')
     * )
     * </pre>
     * 
     * @version 2.7.1
     * @param int $supervisorId Supervisor's ID
     * @param $properties An array of strings containing names of required properties
     * @param $orderField Field to be used for ordering
     * @param $orderBy ASC or DESC
     * @return Array Employee Property List 
     */    
    public function getSubordinatePropertyListBySupervisorId($supervisorId, $properties, $orderField, $orderBy) {
        return $this->getEmployeeDao()->getSubordinatePropertyListBySupervisorId($supervisorId, $properties, $orderField, $orderBy);
    }

    /**
     * Filtering Employee by Sub unit
     * @ignore
     * 
     * @version 2.6.11
     * @param Doctrine_Collection/Array $employeeList Employee List Collection
     * @param String $subUnitId
     * @returns array()
     * @throws PIMServiceException
     * 
     */
    public function filterEmployeeListBySubUnit($employeeList, $subUnitId) {

        if (empty($subUnitId) || $subUnitId == 1) {
            return $employeeList;
        }

        if (empty($employeeList)) {
            $employeeList = $this->getEmployeeList("empNumber", "ASC", true);
        }

        $filteredList = array();
        foreach ($employeeList as $employee) {
            if ($employee->getWorkStation() == $subUnitId) {
                $filteredList[] = $employee;
            }
        }
        
        return $filteredList;
        
    }

    /**
     * Delete Employees for given employee Id List
     * 
     * @version 2.6.11
     * @param array $empList
     * @return boolean , true if successfully deleted 
     * 
     * @throws PIMServiceException
     * 
     * @todo rename method as deleteEmployees($empNumbers )
     * @todo return number of deleted items
     * 
     */
    public function deleteEmployee($empList = array()) {
        return $this->getEmployeeDao()->deleteEmployee($empList);
    }

    /**
     * Checks if the given employee id is in use.
     * 
     * 
     * @version 2.6.11
     * @param  $employeeId
     * @return boolean
     * @throws PIMServiceException
     * 
     * @todo rename method as isExistingEmployeeId( $employeeId ) [DONE]
     */
    public function isExistingEmployeeId($employeeId) {
        return $this->getEmployeeDao()->isExistingEmployeeId($employeeId);
    }

    /**
     * Checks employee already exists for given first middle and last name
     * 
     * @ignore 
     *
     * @version 2.6.11
     * @param string $firstName First Name of the Employee
     * @param string $middle Middle name of the employee
     * @param string $lastname Last name of the employee
     * @return boolean If Employee exists retrun true else return false 
     * @throws PIMServiceException
     * 
     */
    public function checkForEmployeeWithSameName($first, $middle, $last) {
        return $this->getEmployeeDao()->checkForEmployeeWithSameName($first, $middle, $last);
    }

    /**
     * Retrieves the service period of an employee in years
     * as on given date
     * 
     * Returns 0 if employee's joined date is not set.
     * Calculates based on joined data and given date.
     * Does not consider employment terminations happened in between
     * 
     * @version 2.6.11
     * @param int $empNumber
     * @param string $date Any date format supported by strtotime()
     * @return int O if joined date is not set or joined date is after $date
     * @throws PIMServiceException If employee with given ID is not found
     * 
     * @todo Improve year duration calculation 
     */
    public function getEmployeeYearsOfService($empNumber, $date) {
        $employee = $this->getEmployee($empNumber);
        if (!($employee instanceof Employee)) {
            throw new PIMServiceException("Employee with employeeId " . $empNumber . " not found!");
        }
        return $this->getDurationInYears($employee->getJoinedDate(), $date);
    }

    /**
     * Retrieves the duration between two dates in years
     * 
     * If any of the date is empty, it will return 0.
     * 
     * @version 2.6.11
     * @param string $fromDate
     * @param string $toDate
     * @return int 
     * @ignore
     */
    public function getDurationInYears($fromDate, $toDate) {
        $years = 0;
        $secondsOfYear = 60 * 60 * 24 * 365;
        $secondsOfMonth = 60 * 60 * 24 * 30;

        if (!empty($fromDate) && !empty($toDate)) {
            $fromDateTimeStamp = strtotime($fromDate);
            $toDateTimeStamp = strtotime($toDate);

            $timeStampDiff = 0;
            if ($toDateTimeStamp > $fromDateTimeStamp) {
                $timeStampDiff = $toDateTimeStamp - $fromDateTimeStamp;

                $years = floor($timeStampDiff / $secondsOfYear);

                //adjusting the months
                $remainingMonthsTimeStamp = ($timeStampDiff - ($years * $secondsOfYear));
                $months = round($remainingMonthsTimeStamp / $secondsOfMonth);
                $yearByMonth = ($months > 0) ? $months / 12 : 0;

                if (floor($years + $yearByMonth) == ($years + $yearByMonth)) {
                    $years = $this->getBorderPeriodMonths($fromDate, $toDate);
                } else {
                    $years = $years + $yearByMonth;
                }
            }
        }
        return $years;
    }

    /**
     * @ignore
     * @param type $fromDate
     * @param type $toDate
     * @return type 
     */
    private function getBorderPeriodMonths($fromDate, $toDate) {
        $years = 0;
        $secondsOfDay = 60 * 60 * 24;
        $numberOfDaysInYear = 365;
        $secondsOfYear = $secondsOfDay * $numberOfDaysInYear;
        $numberOfMonths = 12;

        $timeStampDiff = strtotime($toDate) - strtotime($fromDate);
        $noOfDays = floor($timeStampDiff / $secondsOfDay);
        $fromYear = date("Y", strtotime($fromDate));
        $toYear = date("Y", strtotime($toDate));
        $ctr = $fromYear;
        $daysCount = 0;

        list($fY, $fM, $fD) = explode("-", $fromDate);
        list($tY, $tM, $tD) = explode("-", $toDate);
        $years = $tY - $fY;

        $temp = date("Y") . "-" . $fM . "-" . $fD;
        $newFromMonthDay = date("m-d", strtotime("-1 day", strtotime($temp)));
        $toMonthDay = $tM . "-" . $tD;

        if ($newFromMonthDay != $toMonthDay) {
            if (($tM - $fM) < 0) {
                $years--;
            } elseif (($tM - $fM) == 0 && ($tD - $fD) < -1) {
                $years--;
            }
        }

        return $years;
    }

    /**
     * Retrieves Workshift details of an employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee number
     * @return EmployeeWorkShift EmployeeWorkShift instance if found or false
     * @throws PIMServiceException
     * 
     * @todo rename method as getEmployeeWorkShift [DONE]
     */
    public function getEmployeeWorkShift($empNumber) {
        return $this->getEmployeeDao()->getEmployeeWorkShift($empNumber);
    }

    /**
     * Retrieves Tax details of an employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee number
     * @return EmpUsTaxExemption EmpUsTaxExemption instance if found or NULL
     * @throws PIMServiceException
     * 
     */
    public function getEmployeeTaxExemptions($empNumber) {
        return $this->getEmployeeDao()->getEmployeeTaxExemptions($empNumber);
    }

    /**
     * Saves Tax Exemptions of an employee 
     * 
     * @version 2.6.11
     * @param EmpUsTaxExemption $empUsTaxExemption
     * @return boolean true always
     * @throws PIMServiceException
     * 
     * @todo return saved EmpUsTaxExemption entry
     */
    public function saveEmployeeTaxExemptions(EmpUsTaxExemption $empUsTaxExemption) {
        return $this->getEmployeeDao()->saveEmployeeTaxExemptions($empUsTaxExemption);
    }

    /**
     * Saves Job details of an employee
     * 
     * @version 2.6.11
     * @param Employee $employee Employee instance
     * @return boolean true always
     * @throws PIMServiceException
     * 
     * @todo Don't return value
     * @todo Save only job details in corresponding DAO method
     * @todo rename method as saveEmployeeJobDetails [DONE]
     */
    public function saveEmployeeJobDetails(Employee $employee) {
        return $this->getEmployeeDao()->saveEmployeeJobDetails($employee);
    }

    /**
     * Retrieves Membership details of an employee
     * 
     * @ignore
     * 
     * @version 2.6.11
     * @param int $empNumber Employee number
     * @return EmployeeMemberDetail A collection of EmployeeMemberDetail
     * @throws PIMServiceException
     * 
     */
    public function getMembershipDetails($empNumber) {
        return $this->getEmployeeDao()->getMembershipDetails($empNumber);
    }

    /**
     * Retrieves details of a membership of an employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee number
     * @param string $membershipCode
     * @return Doctrine_Collection A collection of EmployeeMemberDetail
     * @throws PIMServiceException
     * 
     * @todo Rename the method as  getEmployeeMemberships()
     * @todo Rename EmployeeMemberDetail Entity as EmployeeMembership
     * @todo Make $membershipcode parameter as opational parameter and rename parameter as $membershipId 
     */
    public function getMembershipDetail($empNumber, $membershipCode) {
        return $this->getEmployeeDao()->getMembershipDetail($empNumber, $membershipCode);
    }

    /**
     * Deletes the given Memberships
     * 
     * @version 2.6.11
     * @param array $membershipsToDelete Array of strings with the format
     * "emp_number membership_type_code membership_code"
     * eg: array("1 MEM001 MME001", "2 MEM001 MME002")
     * 
     * @return boolean true always
     * @throws PIMServiceException
     * 
     * @todo Add new method as deleteEmployeeMemberships($empNumber, $membershipIds )
     * @todo return number of items deleted 
     * 
     * 
     */
    public function deleteMembershipDetails($membershipsToDelete) {

        foreach ($membershipsToDelete as $membershipToDelete) {

            $tempArray = explode(" ", $membershipToDelete);

            $empNumber = $tempArray[0];
            $membership = $tempArray[1];

            $this->getEmployeeDao()->deleteMembershipDetails($empNumber, $membership);
        }

        return true;
            
    }
    
    /**
     * Retrieve non-assigned Currency List for given employee for the given salary grade
     * @ignore
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param string $salaryGrade Salary Grade
     * @param boolean $asArray
     * @return Doctrine_Collection/Array Returns Doctrine_Collection of CurrencyType objects
     *  if $asArray is false, otherwise returns an array
     * @throws PIMServiceException     
     * 
     * @todo Remove this method since it's not used anywhere
     */
    public function getUnAssignedCurrencyList($empNumber, $salaryGrade, $asArray = false) {
        return $this->getEmployeeDao()->getUnAssignedCurrencyList($empNumber, $salaryGrade, $asArray);
    }

    /**
     * Retrieve assigned Currency List for the given salary grade
     * 
     * @ignore
     * 
     * @version 2.6.11
     * @param string $salaryGrade Salary Grade
     * @param boolean $asArray
     * @return Doctrine_Collection/Array Returns Doctrine_Collection of CurrencyType objects
     *  if $asArray is false, otherwise returns an array
     * @throws PIMServiceException 
     * 
     * @todo remove this method if it's not used   
     * 
     */
    public function getAssignedCurrencyList($salaryGrade, $asArray = false) {
        return $this->getEmployeeDao()->getAssignedCurrencyList($salaryGrade, $asArray);
    }

    /**
     * Saves basic salary of an employee
     * 
     * @version 2.6.11
     * @param EmpBasicsalary $basicSalary
     * @return boolean true always
     * @throws PIMServiceException
     * 
     * @todo return saved EmpSalary entry 
     * @todo Rename method as saveEmployeeSalary
     * @todo rename Entity as EmpBasicsalary to EmpSalary 
     */
    public function saveEmpBasicsalary(EmpBasicsalary $basicSalary) {
        return $this->getEmployeeDao()->saveEmpBasicsalary($basicSalary);
    }

    /**
     * Deletes a salary of an employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param array $salaryToDelete Array of salary IDs
     * @return boolean true always
     * @throws PIMServiceException
     * 
     * @todo return number deleted items
     * @todo rename method as deleteEmployeeSalaries [DONE]
     * @todo Change parameter to $salaryIds
     * @todo Change EmpBasicSalary ORM to Salary
     */
    public function deleteEmployeeSalaries($empNumber, $salaryToDelete) {
        return $this->getEmployeeDao()->deleteEmployeeSalaries($empNumber, $salaryToDelete);
    }
    
    /**
     * Get Salary Record(s) for given employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee number
     * @param int $empSalaryId Employee Basic Salary ID
     * 
     * @return Collection/EmbBasicsalary  If $empSalaryId is given returns matching 
     * EmbBasicsalary or false if not found. If $empSalaryId is not given, returns 
     * EmbBasicsalary collection. (Empty collection if no records available)
     * @throws DaoException
     * 
     * @todo rename method as getEmployeeSalaries [DONE]
     */
    public function getEmployeeSalaries($empNumber, $empSalaryId = null) {
        return $this->getEmployeeDao()->getEmployeeSalaries($empNumber, $empSalaryId);
    }   

    /**
     * Retrieves Immediate supervisors of an employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @return Doctrine_Collection A collection of ReportTo objects
     * @throws PIMServiceException
     * 
     * @todo Rename the method as getImmediateSupervisors [DONE]
     * @todo return Employee Entities instead of ReportTo Entities
     */
    public function getImmediateSupervisors($empNumber) {
        return $this->getEmployeeDao()->getImmediateSupervisors($empNumber);
    }

    /**
     * Retrieves subordinates of an employee
     * @ignore
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @return Doctrine_Collection A collection of ReportTo objects
     * @throws PIMServiceException
     * 
     * @todo Rename the method as getSubordinates
     */
    public function getSubordinateListForEmployee($empNumber) {
        return $this->getEmployeeDao()->getSubordinateListForEmployee($empNumber);
    }

    /**
     * Retrieves report-to details of given supervisor and subordinate IDs
     * 
     * @ignore
     * @version 2.6.11
     * @param int $supNumber
     * @param int $subNumber
     * @return ReportTo ReportTo instance if found or NULL
     * @throws PIMServiceException
     * 
     * @todo Rename the method as getReportTo()
     */
    public function getReportToObject($supNumber, $subNumber) {
        return $this->getEmployeeDao()->getReportToObject($supNumber, $subNumber);
    }

    /**
     * Deletes report-to details
     * @ignore
     * 
     * @version 2.6.11
     * @param array $supOrSubListToDelete
     * @return boolean true or NULL
     * @throws PIMServiceException
     * 
     * @todo Array elements can also be arrays rather than space-separated values
     * @todo Currently it returns last deleted record's return value instead return 
     * an overall value
     * @todo Add new deleteEmployeeSubordinates($empNumber , $subordinateIds)  method
     * @todo Add new deleteEmployeeSupervisors($empNumber , $supervisorIds)  method
     * $todo Add new saveEmployeeReportTo(ReportTo $reportTo)
     * 
     */
    public function deleteReportToObject($supOrSubListToDelete) {

        foreach ($supOrSubListToDelete as $supOrSubToDelete) {

            $tempArray = explode(" ", $supOrSubToDelete);

            $supNumber = $tempArray[0];
            $subNumber = $tempArray[1];
            $reportingMethod = $tempArray[2];

            $state = $this->getEmployeeDao()->deleteReportToObject($supNumber, $subNumber, $reportingMethod);
        }

        return $state;
            
    }

    /**
     * Check if user with given userId is an admin
     * @param string $userId
     * @return bool - True if given user is an admin, false if not
     * @ignore
     *
     * @todo Move method to Auth Service
     */
    public function isAdmin($userId) {
        return $this->getEmployeeDao()->isAdmin($userId);
    }

    /**
     * Get list of all employees work emails and other emails
     * 
     * Work email index = 'emp_work_email'
     * Other email index = 'emp_oth_email'
     * 
     * @return DoctrineCollection work emails and other emails 
     * @ignore
     * 
     * @todo Look at usages and improve them. (use ajax instead of loading all
     *       emails to template)
     */
    public function getEmailList() {
        return $this->getEmployeeDao()->getEmailList();
    }

    /**
     * Get emp numbers of all subordinates in the system
     * 
     * @return Array Array of subordinate employee numbers 
     * @ignore
     * 
     * @todo Get the result as a PHP array in Doctrine rather than creating the
     * array afterwards.
     * @todo If not in use, remove method from Service and DAO
     */
    public function getSubordinateIdList() {
        return $this->getEmployeeDao()->getSubordinateIdList();
    }

    /**
     * Terminate employment of given employee.
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @param int $empTerminationId Employee Termination Id
     * @return int 1 if successfull, 0 if empNumber is not available 
     * 
     * @todo Change to take EmpTermination object. Dao should save 
     * EmpTermination and update termination id in employee table in one
     * transaction.
     * 
     * @todo throw an exception if not successfull, no return type
     */
    public function terminateEmployment($empNumber, $empTerminationId) {
        return $this->getEmployeeDao()->terminateEmployment($empNumber, $empTerminationId);
    }

    /**
     * Activates terminated employment for given employee
     * 
     * @version 2.6.11
     * @param int $empNumber Employee Number
     * @return int 1 if successfull, 0 if empNumber is not available 
     * 
     * @todo Rename to activateTerminatedEmployment
     */
    public function activateEmployment($empNumber) {
        return $this->getEmployeeDao()->activateEmployment($empNumber);
    }

    /**
     * Get EmpTermination object with given Id.
     * 
     * @version 2.6.11
     * @param int $terminatedId Termination Id
     * @return EmpTermination EmpTermination object
     * 
     * @todo raname method as getEmployeeTerminationDetails 
     */
    public function getEmpTerminationById($terminatedId) {
        return $this->getEmployeeDao()->getEmpTerminationById($terminatedId);
    }
    
    /**
     * Get Employees under the given subunits
     * 
     * Only returns the employees in given subunits, not in sub unit hierarchies.
     * 
     * @param string/array $subUnits Sub Unit IDs
     * @param boolean $includeTerminatedEmployees if true, includes terminated employees
     * 
     * @return Doctrine_Collection of Employee objects
     */
    public function getEmployeesBySubUnit($subUnits, $includeTerminatedEmployees = false) {
        return $this->getEmployeeDao()->getEmployeesBySubUnit($subUnits, $includeTerminatedEmployees); 
    } 
    
   /**
     * Get employee list after sorting and filtering using given parameters.
     *
     * @param array $sortField String or Array
     * @param array $sortOrder String or Array 
     * @param array $filters 
     * @param int $offset
     * @param int $limit 
     * 
     * @return Employee array of Employee entities match with filters
     * 
     * @todo Rename to searchEmployees(ParameterHolder $parameterObject)
     * @todo Use an instance of a parameter holder instead of set of parameters
     */
    public function searchEmployeeList($sortField = 'empNumber', $sortOrder = 'asc', array $filters = null, $offset = null, $limit = null) {
        return $this->getEmployeeDao()->searchEmployeeList($sortField,$sortOrder,$filters,$offset,$limit);
    }
    
    /**
     * Get Search Employee Count
     *
     * @param array $filters
     * 
     * @return Inteager
     * @todo Use a parameter object instead of $filters
     */
    public function getSearchEmployeeCount(array $filters = null) {
        return $this->getEmployeeDao()->getSearchEmployeeCount($filters);
    }

}
