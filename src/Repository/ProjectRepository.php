<?php

namespace Tuxi\Portfolio\Repository;

use DateTime;
use Tuxi\Portfolio\Entity\Project;

/**
 * Object allowing access to the data of projects entity.
 *
 * @author Titux Metal <tituxmetal@gmail.com>
 */
class ProjectRepository extends Repository {
  
  /**
   * Return a list of all projects, sorted by id.
   * 
   * @return array A list of all projects.
   */
  public function findAll() {
    $sql = "
      SELECT id, name, description, image, main_link, sources_link, created
      FROM projects
      ORDER BY id ASC
    ";
    $result = $this->getDb()->fetchAll($sql);
    
    $projects = [];
    foreach($result as $row) {
      $projectId = $row['id'];
      $projects[$projectId] = $this->buildDomainObject($row);
    }
    
    return $projects;
  }
  
  /**
   * Creates a Project object based on a database row.
   * 
   * @param array $row The database row containing Project data.
   * @return Tuxi\Portfolio\Entity\Project The Project object based on
   * the database row.
   */
  protected function buildDomainObject(array $row) {
    $project = new Project();
    $project->setId($row['id']);
    $project->setName($row['name']);
    $project->setDescription($row['description']);
    
    $image = new ImageRepository($this->getDb());
    $project->setImage($image->findById($row['image']));
    
    $link = new LinkRepository($this->getDb());
    $project->setMainLink($link->findById($row['main_link']));
    $project->setSourcesLink($link->findById($row['sources_link']));
    
    $project->setCreated(new DateTime($row['created']));
    
    return $project;
  }

}
