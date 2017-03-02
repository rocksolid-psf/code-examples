<?php

namespace Drupal\ss_location\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityStorageInterface;

const SS_LOCATION_FIELDS = [
  'Name',
  'Permalink',
  'id',
  'StatusSite',
  'LocationManager',
  'MainHeaderImage',
  'MainHeaderText',
  'ServiceKDV',
  'MainServiceKDVImage',
  'MainServiceKDVTitle',
  'MainServiceKDVAge',
  'MainServiceKDVText',
  'ServiceBSO',
  'MainServiceBSOImage',
  'MainServiceBSOTitle',
  'MainServiceBSOAge',
  'MainServiceBSOText',
  'ServiceVSO',
  'ServiceTSO',
  'ServiceNSO',
  'ServicePSZ',
  'MainServicePSZImage',
  'MainServicePSZTitle',
  'MainServicePSZAge',
  'MainServicePSZText',
  'HoursKDV',
  'HoursBSO',
  'HoursPSZ',
  'MainDayAtTitle',
  'MainDayAtText',
  'MainDayAtImage',
  'MainTourImage',
  'SocialFacebook',
  'SocialFacebookId',
  'MainOurTitle',
  'MainOurText',
  'MainOurImage',
  'Latitude',
  'Longitude',
  'StreetAddress',
  'Postcode',
  'MainBannerStatus',
  'MainBannerTitle1',
  'MainBannerTitle2',
  'MainBannerTitle3',
  'MainBannerSubTitle1',
  'MainBannerSubTitle2',
  'MainBannerSubTitle3',
  'MainBannerImage1',
  'MainBannerImage2',
  'MainBannerImage3',
  'MainRegisterTitle',
  'MainRegisterText',
  'Telephone',
  'MainPackagesText',
  'MainRatesImage',
  'Branch',
  'BranchName',
  'BranchStreetAddress',
  'BranchPostcode',
  'BranchLatitude',
  'BranchLongitude',
  'BranchTelephone',
  'MainPolicyImage',
  'DataChildCarePlanKDV',
  'DataChildCarePlanBSO',
  'DataChildCarePlanPSZ',
  'DataChildCarePlanKDVBranch',
  'DataChildCarePlanBSOBranch',
  'DataChildCarePlanPSZBranch',
  'DataGGDReportLRKKDV',
  'DataGGDReportLRKBSO',
  'DataGGDReportLRKPSZ',
  'DataGGDReportLRKBranch',
  'LRKKDV',
  'LRKBSO',
  'LRKPSZ',
  'BranchLRKKDV',
  'BranchLRKBSO',
  'ServiceKDVHeaderText',
  'ServicePSZHeaderText',
  'ServiceBSOHeaderText',
  'ServiceKDVHeaderImage',
  'ServicePSZHeaderImage',
  'ServiceBSOHeaderImage',
  'ServiceBSOAdditionalTextTSO',
  'ServiceBSOAdditionalTextVSO',
  'ServiceBSOAdditionalText',
  'ServiceKDVSliderTitle',
  'ServiceKDVSliderText',
  'ServiceKDVSliderImage1',
  'ServiceKDVSliderImage2',
  'ServiceKDVSliderImage3',
  'ServiceKDVSliderImage4',
  'ServiceKDVSliderImage5',
  'ServiceBSOSliderTitle',
  'ServiceBSOSliderText',
  'ServiceBSOSliderImage1',
  'ServiceBSOSliderImage2',
  'ServiceBSOSliderImage3',
  'ServiceBSOSliderImage4',
  'ServiceBSOSliderImage5',
  'ServicePSZSliderTitle',
  'ServicePSZSliderText',
  'ServicePSZSliderImage1',
  'ServicePSZSliderImage2',
  'ServicePSZSliderImage3',
  'ServicePSZSliderImage4',
  'ServicePSZSliderImage5',
  'ServiceKDVGroupsText',
  'ServiceKDVGroupsImage',
  'ServiceBSOGroupsText',
  'ServiceBSOGroupsImage',
  'ServicePSZGroupsText',
  'ServicePSZGroupsImage',
  'ServiceKDVSchoolText',
  'ServiceKDVSchoolImage',
  'ServiceBSOSchoolText',
  'ServiceBSOSchoolImage',
  'ServicePSZSchoolText',
  'ServicePSZSchoolImage',
  'ServiceKDVRatesText',
  'ServiceBSORatesText',
  'ServicePSZRatesText',
  'OurIntroText1',
  'OurIntroText2',
  'OurSliderTitle',
  'OurSliderText',
  'OurSliderImage1',
  'OurSliderImage2',
  'OurSliderImage3',
  'OurSliderImage4',
  'OurSliderImage5',
  'OurInsideText',
  'OurInsideImage',
  'OurActiveText',
  'OurActiveImage',
  'OurOutdoorText',
  'OurOutdoorImage'
];

/**
 * Defines the Location entity class.
 *
 * @ContentEntityType(
 *   id = "ss_location",
 *   label = @Translation("SmallSteps Location Entity"),
 *   handlers = {
 *     "access" = "Drupal\ss_location\LocationAccessControlHandler",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ss_location\LocationListBuilder",
 *     "views_data" = "Drupal\ss_location\LocationViewsData",
 *   },
 *   base_table = "channela_ss_dashboard.Locations",
 *   admin_permission = "administer location content",
 *   persistent_cache = FALSE,
 *   list_cache_contexts = { "location_view_grants" },
 *   entity_keys = {
 *     "id" = "permalink",
 *     "lid" = "id",
 *   },
 *   links = {
 *     "canonical" = "/kinderopvang/{ss_location}",
 *     "service" = "/kinderopvang/{ss_location}/{service}",
 *     "about" = "/kinderopvang/{ss_location}/ons-kinderdagverblijf",
 *     "tour" = "/kinderopvang/{ss_location}/rondleiding-aanvragen",
 *     "registration" = "/kinderopvang/{ss_location}/aanmelden",
 *     "calculator" = "/kinderopvang/{ss_location}/kostencalculator",
 *     "collection" = "/admin/content/location",
 *   },
 *   field_ui_base_route = "entity.ss_location.admin_form",
 * )
 */
class Location extends ContentEntityBase {

  protected static $entity_name = 'ss_location';

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage, array &$values) {
    parent::preCreate($storage, $values);
    if (empty($values['type'])) {
      $values['type'] = $storage->getEntityTypeId();
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    foreach(SS_LOCATION_FIELDS as $name) {
      $fields[strtolower($name)] = BaseFieldDefinition::create('string')
        ->setLabel(t($name))
        ->setDisplayOptions('view', [
          'label' => 'hidden',
          'type' => 'string',
          'weight' => -5,
        ]);
    }

    $fields['city'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('City'))
      ->setDescription(t('The city of the location entity.'))
      ->setSetting('target_type', 'ss_city')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'entity_reference',
        'weight' => -5,
      ]);

    $fields['branchcity'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Branch city'))
      ->setDescription(t('The branch city of the location entity.'))
      ->setSetting('target_type', 'ss_city')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'entity_reference',
        'weight' => -5,
      ]);

    return $fields;
  }

  /**
   * Returns the mainheaderimage.
   *
   * @return string
   */
  public function getMainHeaderImage() {
    $mainheaderimage = $this->get('mainheaderimage')->value;
    if (!$mainheaderimage) {
      $mainheaderimage = gc('LocationMainHeaderImage');
    }
    return $mainheaderimage;
  }

  /**
   * Returns the mainheadertext.
   *
   * @return string
   */
  public function getMainHeaderText() {
    $mainheadertext = $this->get('mainheadertext')->value;
    if (!$mainheadertext) {
      $mainheadertext = gc('LocationMainHeaderText');
    }
    return $mainheadertext;
  }

  public function getId() {
    return $this->get('id')->value;
  }

  /**
   * Returns the name.
   *
   * @return string
   */
  public function getName() {
    return $this->get('name')->value;
  }

  public function getStatus() {
    return $this->get('statussite')->value;
  }

  /**
   * Returns the path.
   *
   * @return string
   */
  public function getPath() {
    return $this->get('permalink')->value;
  }

  public function getLocationManager() {
    return $this->get('locationmanager')->value;
  }

  public function getManagerInfo() {
    return ss_location_manager($this->get('locationmanager')->value);
  }

  public function getMainServiceKDVImage() {
    $value = $this->get('mainservicekdvimage')->value;
    if (!$value) {
      $value = gc('LocationMainServiceKDVImage');
    }
    return $value;
  }

  public function getMainServiceKDVTitle() {
    $value = $this->get('mainservicekdvtitle')->value;
    if (!$value) {
      $value = gc('LocationMainServiceKDVTitle');
    }
    return $value;
  }

  public function getMainServiceKDVAge() {
    $value = $this->get('mainservicekdvage')->value;
    if (!$value) {
      $value = gc('LocationMainServiceKDVAge');
    }
    return $value;
  }

  public function getMainServiceKDVText() {
    $value = $this->get('mainservicekdvtext')->value;
    if (!$value) {
      $value = gc('LocationMainServiceKDVText');
    }
    return $value;
  }

  public function getMainServiceBSOImage() {
    $value = $this->get('mainservicebsoimage')->value;
    if (!$value) {
      $value = gc('LocationMainServiceBSOImage');
    }
    return $value;
  }

  public function getMainServiceBSOTitle() {
    $value = $this->get('mainservicekdvtitle')->value;
    if (!$value) {
      $value = gc('LocationMainServiceBSOTitle');
    }
    return $value;
  }

  public function getMainServiceBSOAge() {
    $value = $this->get('mainservicebsoage')->value;
    if (!$value) {
      $value = gc('LocationMainServiceBSOAge');
    }
    return $value;
  }

  public function getMainServiceBSOText() {
    $value = $this->get('mainservicebsotext')->value;
    if (!$value) {
      $value = gc('LocationMainServiceBSOText');
    }
    return $value;
  }

  public function getMainServicePSZImage() {
    $value = $this->get('mainservicepszimage')->value;
    if (!$value) {
      $value = gc('LocationMainServicePSZImage');
    }
    return $value;
  }

  public function getMainServicePSZTitle() {
    $value = $this->get('mainservicepsztitle')->value;
    if (!$value) {
      $value = gc('LocationMainServicePSZTitle');
    }
    return $value;
  }

  public function getMainServicePSZAge() {
    $value = $this->get('mainservicepszage')->value;
    if (!$value) {
      $value = gc('LocationMainServicePSZAge');
    }
    return $value;
  }

  public function getMainServicePSZText() {
    $value = $this->get('mainservicepsztext')->value;
    if (!$value) {
      $value = gc('LocationMainServicePSZText');
    }
    return $value;
  }

  public function getHoursKDV() {
    $hourskdv = $this->get('hourskdv')->value;
    if ($hourskdv) {
      $hourskdv = unserialize($hourskdv);
    }
    return $hourskdv;
  }

  public function getHoursBSO() {
    $hoursbso = $this->get('hoursbso')->value;
    if ($hoursbso) {
      $hoursbso = unserialize($hoursbso);
    }
    return $hoursbso;
  }

  public function getHoursPSZ() {
    $hourspsz = $this->get('hourspsz')->value;
    if ($hourspsz) {
      $hourspsz = unserialize($hourspsz);
    }
    return $hourspsz;
  }

  public function getMainDayAtTitle() {
    $value = $this->get('maindayattitle')->value;
    if (!$value) {
      $value = gc('LocationMainDayAtTitle');
    }
    return $value;
  }

  public function getMainDayAtText() {
    $value = $this->get('maindayattext')->value;
    if (!$value) {
      $value = gc('LocationMainDayAtText');
    }
    return $value;
  }

  public function getMainDayAtImage() {
    $value = $this->get('maindayatimage')->value;
    if (!$value) {
      $value = gc('LocationMainDayAtImage');
    }
    return $value;
  }

  public function getMainTourImage() {
    $value = $this->get('maintourimage')->value;
    if (!$value) {
      $value = gc('LocationMainTourImage');
    }
    return $value;
  }

  public function getTourSchedules() {
    return ss_location_tour_schedules($this->get('id')->value);
  }

  public function getSocialFacebook() {
    $facebook = $this->get('socialfacebook')->value;
    if (strpos($facebook, 'facebook.com') !== FALSE) {
      $facebook = 'https://www.' . substr($facebook, strpos($facebook, 'facebook.com'));
    }
    if (strpos($facebook, 'facebook.com') === FALSE) {
      $facebook = trim($facebook, '/');
      $facebook = 'https://www.facebook.com/' . $facebook;
    }
    return $facebook;
  }

  public function getSocialFacebookId() {
    return $this->get('socialfacebookid')->value;
  }

  public function getSocialFacebookReviews() {
    return ss_location_fb_page_info($this->get('socialfacebookid')->value);
  }

  public function getServiceKDV() {
    return $this->get('servicekdv')->value;
  }

  public function getServiceBSO() {
    return $this->get('servicebso')->value;
  }

  public function getServiceVSO() {
    return $this->get('servicevso')->value;
  }

  public function getServiceTSO() {
    return $this->get('servicetso')->value;
  }

  public function getServiceNSO() {
    return $this->get('servicenso')->value;
  }

  public function getServicePSZ() {
    return $this->get('servicepsz')->value;
  }

  public function getMainOurTitle() {
    $value = $this->get('mainourtitle')->value;
    if (!$value) {
      $value = gc('LocationMainOurTitle');
    }
    return $value;
  }

  public function getMainOurText() {
    $value = $this->get('mainourtext')->value;
    if (!$value) {
      $value = gc('LocationMainOurText');
    }
    return $value;
  }

  public function getMainOurImage() {
    $value = $this->get('mainourimage')->value;
    if (!$value) {
      $value = gc('LocationMainOurImage');
    }
    return $value;
  }

  public function getStreetLatitude() {
    return $this->get('latitude')->value;
  }

  public function getStreetLongitude() {
    return $this->get('longitude')->value;
  }

  public function getCity() {
    $city = $this->get('city')->entity;
    if ($city) {
      return $city->getName();
    }
    return NULL;
  }

  public function getStreetAddress() {
    return $this->get('streetaddress')->value;
  }

  public function getPostCode() {
    return $this->get('postcode')->value;
  }

  public function getMainBannerStatus() {
    return $this->get('mainbannerstatus')->value;
  }

  public function getMainBannerTitle1() {
    $value = $this->get('mainbannertitle1')->value;
    if (!$value) {
      $value = gc('LocationMainBannerTitle1');
    }
    return $value;
  }

  public function getMainBannerTitle2() {
    $value = $this->get('mainbannertitle2')->value;
    if (!$value) {
      $value = gc('LocationMainBannerTitle2');
    }
    return $value;
  }

  public function getMainBannerTitle3() {
    $value = $this->get('mainbannertitle3')->value;
    if (!$value) {
      $value = gc('LocationMainBannerTitle3');
    }
    return $value;
  }

  public function getMainBannerSubTitle1() {
    $value = $this->get('mainbannersubtitle1')->value;
    if (!$value) {
      $value = gc('LocationMainBannerSubTitle1');
    }
    return $value;
  }

  public function getMainBannerSubTitle2() {
    $value = $this->get('mainbannersubtitle2')->value;
    if (!$value) {
      $value = gc('LocationMainBannerSubTitle2');
    }
    return $value;
  }

  public function getMainBannerSubTitle3() {
    $value = $this->get('mainbannersubtitle3')->value;
    if (!$value) {
      $value = gc('LocationMainBannerSubTitle3');
    }
    return $value;
  }

  public function getMainBannerImage1() {
    $value = $this->get('mainbannerimage1')->value;
    if (!$value) {
      $value = gc('LocationMainBannerImage1');
    }
    return $value;
  }

  public function getMainBannerImage2() {
    $value = $this->get('mainbannerimage2')->value;
    if (!$value) {
      $value = gc('LocationMainBannerImage2');
    }
    return $value;
  }

  public function getMainBannerImage3() {
    $value = $this->get('mainbannerimage3')->value;
    if (!$value) {
      $value = gc('LocationMainBannerImage3');
    }
    return $value;
  }

  public function getMainRegisterTitle() {
    $value = $this->get('mainregistertitle')->value;
    if (!$value) {
      $value = gc('LocationMainRegisterTitle');
    }
    return $value;
  }

  public function getMainRegisterText() {
    $value = $this->get('mainregistertext')->value;
    if (!$value) {
      $value = gc('LocationMainRegisterText');
    }
    return $value;
  }

  public function getFAQ($service = NULL) {
    $query = \Drupal::database()->select('channela_ss_dashboard.FAQsRel', 'fr');
    $query->join('channela_ss_dashboard.FAQs', 'fs', 'fs.Id = fr.FAQ');
    $query->addField('fr', 'FAQ');
    switch ($service) {
      case 'KDV':
        $query->condition('fr.Rel', 1);
        $query->condition('fr.Type', 1);
        break;
      case 'PSZ':
        $query->condition('fr.Rel', 6);
        $query->condition('fr.Type', 1);
        break;
      case 'BSO':
        $query->condition('fr.Rel', 2);
        $query->condition('fr.Type', 1);
        break;
      default:
        $query->condition('fr.Rel', 1);
        $query->condition('fr.Type', 2);
        break;
    }
    $query->orderBy('fs.FAQOrder');
    $query->range(0, 10);
    $ids = $query->execute()->fetchCol();

    $faq_list = \Drupal::entityTypeManager()->getStorage('ss_faq')->loadMultiple($ids);

    $list = [];
    foreach ($faq_list as $faq) {
      $list[] = [
        'question' => $faq->getQuestion(),
        'answer' => $faq->getAnswer()
      ];
    }

    return $list;
  }

  public function getTelephone() {
    return $this->get('telephone')->value;
  }

  public function getMainPackagesText() {
    return $this->get('mainpackagestext')->value;
  }

  public function getMainPackagesList() {
    return ss_location_contracts($this->get('id')->value, 6);
  }

  public function getServiceKDVPackagesList() {
    return ss_location_service_contracts($this->get('id')->value, 'KDV');
  }

  public function getServicePSZPackagesList() {
    return ss_location_service_contracts($this->get('id')->value, 'PSZ');
  }

  public function getServiceBSOPackagesList() {
    return ss_location_service_contracts($this->get('id')->value, 'BSO');
  }

  public function getServiceVSOPackagesList() {
    return ss_location_service_contracts($this->get('id')->value, 'VSO');
  }

  public function getServiceTSOPackagesList() {
    return ss_location_service_contracts($this->get('id')->value, 'TSO');
  }

  public function getMainRatesImage() {
    $value = $this->get('mainratesimage')->value;
    if (!$value) {
      $value = gc('LocationMainRatesImage');
    }
    return $value;
  }

  public function getCityNeighbourhood() {
    return mb_strtolower($this->get('city')->entity->getNeighbourhood());
  }

  public function getBranch() {
    return $this->get('branch')->value;
  }

  public function getBranchName() {
    return $this->get('branchname')->value;
  }

  public function getBranchStreetAddress() {
    return $this->get('branchstreetaddress')->value;
  }

  public function getBranchPostcode() {
    return $this->get('branchpostcode')->value;
  }

  public function getBranchCity() {
    if ($this->get('branchcity')->value) {
      return $this->get('branchcity')->entity->getName();
    }
    return NULL;
  }

  public function getBranchLatitude() {
    return $this->get('branchlatitude')->value;
  }

  public function getBranchLongitude() {
    return $this->get('branchlongitude')->value;
  }

  public function getBranchTelephone() {
    return $this->get('branchtelephone')->value;
  }

  public function getMainPolicyImage() {
    $value = $this->get('mainpolicyimage')->value;
    if (!$value) {
      $value = gc('LocationMainPolicyImage');
    }
    return $value;
  }

  public function getDataChildCarePlanKDV() {
    return $this->get('datachildcareplankdv')->value;
  }

  public function getDataChildCarePlanBSO() {
    return $this->get('datachildcareplanbso')->value;
  }

  public function getDataChildCarePlanPSZ() {
    return $this->get('datachildcareplanpsz')->value;
  }

  public function getDataChildCarePlanKDVBranch() {
    return $this->get('datachildcareplankdvbranch')->value;
  }

  public function getDataChildCarePlanBSOBranch() {
    return $this->get('datachildcareplanbsobranch')->value;
  }

  public function getDataChildCarePlanPSZBranch() {
    return $this->get('datachildcareplanpszbranch')->value;
  }

  public function getDataGGDReportLRKKDV() {
    return $this->get('dataggdreportlrkkdv')->value;
  }

  public function getDataGGDReportLRKBSO() {
    return $this->get('dataggdreportlrkbso')->value;
  }

  public function getDataGGDReportLRKPSZ() {
    return $this->get('dataggdreportlrkpsz')->value;
  }

  public function getDataGGDReportLRKBranch() {
    return $this->get('dataggdreportlrkbranch')->value;
  }

  public function getLRKKDV() {
    return $this->get('lrkkdv')->value;
  }

  public function getLRKBSO() {
    return $this->get('lrkbso')->value;
  }

  public function getLRKPSZ() {
    return $this->get('lrkpsz')->value;
  }

  public function getBranchLRKKDV() {
    return $this->get('branchlrkkdv')->value;
  }

  public function getBranchLRKBSO() {
    return $this->get('branchlrkbso')->value;
  }

  public function getServiceKDVHeaderText() {
    $value = $this->get('servicekdvheadertext')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVHeaderText');
    }
    return $value;
  }

  public function getServicePSZHeaderText() {
    $value = $this->get('servicepszheadertext')->value;
    if (!$value) {
      $value = gc('LocationServicePSZHeaderText');
    }
    return $value;
  }

  public function getServiceBSOHeaderText() {
    $value = $this->get('servicebsoheadertext')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOHeaderText');
    }
    return $value;
  }

  public function getServiceKDVHeaderImage() {
    $value = $this->get('servicekdvheaderimage')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVHeaderImage');
    }
    return $value;
  }

  public function getServicePSZHeaderImage() {
    $value = $this->get('servicepszheaderimage')->value;
    if (!$value) {
      $value = gc('LocationServicePSZHeaderImage');
    }
    return $value;
  }

  public function getServiceBSOHeaderImage() {
    $value = $this->get('servicebsoheaderimage')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOHeaderImage');
    }
    return $value;
  }

  public function getServiceBSOAdditionalTextTSO() {
    $value = $this->get('servicebsoadditionaltexttso')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOAdditionalTextTSO');
    }
    return $value;
  }

  public function getServiceBSOAdditionalTextVSO() {
    $value = $this->get('servicebsoadditionaltextvso')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOAdditionalTextVSO');
    }
    return $value;
  }

  public function getServiceBSOAdditionalText() {
    $value = $this->get('servicebsoadditionaltext')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOAdditionalText');
    }
    return $value;
  }

  public function getServiceKDVSliderTitle() {
    $value = $this->get('servicekdvslidertitle')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVSliderTitle');
    }
    return $value;
  }

  public function getServiceKDVSliderText() {
    $value = $this->get('servicekdvslidertext')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVSliderText');
    }
    return $value;
  }

  public function getServiceKDVSliderImage1() {
    $value = $this->get('servicekdvsliderimage1')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVSliderImage1');
    }
    return $value;
  }

  public function getServiceKDVSliderImage2() {
    $value = $this->get('servicekdvsliderimage2')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVSliderImage2');
    }
    return $value;
  }

  public function getServiceKDVSliderImage3() {
    $value = $this->get('servicekdvsliderimage3')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVSliderImage3');
    }
    return $value;
  }

  public function getServiceKDVSliderImage4() {
    $value = $this->get('servicekdvsliderimage4')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVSliderImage4');
    }
    return $value;
  }

  public function getServiceKDVSliderImage5() {
    $value = $this->get('servicekdvsliderimage5')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVSliderImage5');
    }
    return $value;
  }

  public function getServiceBSOSliderTitle() {
    $value = $this->get('servicebsoslidertitle')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOSliderTitle');
    }
    return $value;
  }

  public function getServiceBSOSliderText() {
    $value = $this->get('servicebsoslidertext')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOSliderText');
    }
    return $value;
  }

  public function getServiceBSOSliderImage1() {
    $value = $this->get('servicebsosliderimage1')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOSliderImage1');
    }
    return $value;
  }

  public function getServiceBSOSliderImage2() {
    $value = $this->get('servicebsosliderimage2')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOSliderImage2');
    }
    return $value;
  }

  public function getServiceBSOSliderImage3() {
    $value = $this->get('servicebsosliderimage3')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOSliderImage3');
    }
    return $value;
  }

  public function getServiceBSOSliderImage4() {
    $value = $this->get('servicebsosliderimage4')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOSliderImage4');
    }
    return $value;
  }

  public function getServiceBSOSliderImage5() {
    $value = $this->get('servicebsosliderimage5')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOSliderImage5');
    }
    return $value;
  }

  public function getServicePSZSliderTitle() {
    $value = $this->get('servicepszslidertitle')->value;
    if (!$value) {
      $value = gc('LocationServicePSZSliderTitle');
    }
    return $value;
  }

  public function getServicePSZSliderText() {
    $value = $this->get('servicepszslidertext')->value;
    if (!$value) {
      $value = gc('LocationServicePSZSliderText');
    }
    return $value;
  }

  public function getServicePSZSliderImage1() {
    $value = $this->get('servicepszsliderimage1')->value;
    if (!$value) {
      $value = gc('LocationServicePSZSliderImage1');
    }
    return $value;
  }

  public function getServicePSZSliderImage2() {
    $value = $this->get('servicepszsliderimage2')->value;
    if (!$value) {
      $value = gc('LocationServicePSZSliderImage2');
    }
    return $value;
  }

  public function getServicePSZSliderImage3() {
    $value = $this->get('servicepszsliderimage3')->value;
    if (!$value) {
      $value = gc('LocationServicePSZSliderImage3');
    }
    return $value;
  }

  public function getServicePSZSliderImage4() {
    $value = $this->get('servicepszsliderimage4')->value;
    if (!$value) {
      $value = gc('LocationServicePSZSliderImage4');
    }
    return $value;
  }

  public function getServicePSZSliderImage5() {
    $value = $this->get('servicepszsliderimage5')->value;
    if (!$value) {
      $value = gc('LocationServicePSZSliderImage5');
    }
    return $value;
  }

  public function getServiceKDVGroupsText() {
    $value = $this->get('servicekdvgroupstext')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVGroupsText');
    }
    return $value;
  }

  public function getServiceKDVGroupsImage() {
    $value = $this->get('servicekdvgroupsimage')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVGroupsImage');
    }
    return $value;
  }

  public function getServiceBSOGroupsText() {
    $value = $this->get('servicebsogroupstext')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOGroupsText');
    }
    return $value;
  }

  public function getServiceBSOGroupsImage() {
    $value = $this->get('servicebsogroupsimage')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOGroupsImage');
    }
    return $value;
  }

  public function getServicePSZGroupsText() {
    $value = $this->get('servicepszgroupstext')->value;
    if (!$value) {
      $value = gc('LocationServicePSZGroupsText');
    }
    return $value;
  }

  public function getServicePSZGroupsImage() {
    $value = $this->get('servicepszgroupsimage')->value;
    if (!$value) {
      $value = gc('LocationServicePSZGroupsImage');
    }
    return $value;
  }

  public function getServiceKDVSchoolText() {
    $value = $this->get('servicekdvschooltext')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVSchoolText');
    }
    return $value;
  }

  public function getServiceKDVSchoolImage() {
    $value = $this->get('servicekdvschoolimage')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVSchoolImage');
    }
    return $value;
  }

  public function getServiceBSOSchoolText() {
    $value = $this->get('servicebsoschooltext')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOSchoolText');
    }
    return $value;
  }

  public function getServiceBSOSchoolImage() {
    $value = $this->get('servicebsoschoolimage')->value;
    if (!$value) {
      $value = gc('LocationServiceBSOSchoolImage');
    }
    return $value;
  }

  public function getServicePSZSchoolText() {
    $value = $this->get('servicepszschooltext')->value;
    if (!$value) {
      $value = gc('LocationServicePSZSchoolText');
    }
    return $value;
  }

  public function getServicePSZSchoolImage() {
    $value = $this->get('servicepszschoolimage')->value;
    if (!$value) {
      $value = gc('LocationServicePSZSchoolImage');
    }
    return $value;
  }

  public function getServiceKDVRatesText() {
    $value = $this->get('servicekdvratestext')->value;
    if (!$value) {
      $value = gc('LocationServiceKDVRatesText');
    }
    return $value;
  }

  public function getServiceBSORatesText() {
    $value = $this->get('servicebsoratestext')->value;
    if (!$value) {
      $value = gc('LocationServiceBSORatesText');
    }
    return $value;
  }

  public function getServicePSZRatesText() {
    $value = $this->get('servicepszratestext')->value;
    if (!$value) {
      $value = gc('LocationServicePSZRatesText');
    }
    return $value;
  }

  public function getServiceKDVRatesCalculationTitle() {
    return gc('LocationServiceKDVRatesCalculationTitle');
  }

  public function getServiceBSORatesCalculationTitle() {
    return gc('LocationServiceBSORatesCalculationTitle');
  }

  public function getServiceKDVRatesCalculationText() {
    return gc('LocationServiceKDVRatesCalculationText');
  }

  public function getServiceBSORatesCalculationText() {
    return gc('LocationServiceBSORatesCalculationText');
  }

  public function getOurIntroText1() {
    $value = $this->get('ourintrotext1')->value;
    if (!$value) {
      $value = gc('LocationOurIntroText1');
    }
    return $value;
  }

  public function getOurIntroText2() {
    $value = $this->get('ourintrotext2')->value;
    if (!$value) {
      $value = gc('LocationOurIntroText2');
    }
    return $value;
  }

  public function getOurSliderTitle() {
    $value = $this->get('ourslidertitle')->value;
    if (!$value) {
      $value = gc('LocationOurSliderTitle');
    }
    return $value;
  }

  public function getOurSliderText() {
    $value = $this->get('ourslidertext')->value;
    if (!$value) {
      $value = gc('LocationOurSliderText');
    }
    return $value;
  }

  public function getOurSliderImage1() {
    $value = $this->get('oursliderimage1')->value;
    if (!$value) {
      $value = gc('LocationOurSliderImage1');
    }
    return $value;
  }

  public function getOurSliderImage2() {
    $value = $this->get('oursliderimage2')->value;
    if (!$value) {
      $value = gc('LocationOurSliderImage2');
    }
    return $value;
  }

  public function getOurSliderImage3() {
    $value = $this->get('oursliderimage3')->value;
    if (!$value) {
      $value = gc('LocationOurSliderImage3');
    }
    return $value;
  }

  public function getOurSliderImage4() {
    $value = $this->get('oursliderimage4')->value;
    if (!$value) {
      $value = gc('LocationOurSliderImage4');
    }
    return $value;
  }

  public function getOurSliderImage5() {
    $value = $this->get('oursliderimage5')->value;
    if (!$value) {
      $value = gc('LocationOurSliderImage5');
    }
    return $value;
  }

  public function getOurInsideText() {
    $value = $this->get('ourinsidetext')->value;
    if (!$value) {
      $value = gc('LocationOurInsideText');
    }
    return $value;
  }

  public function getOurInsideImage() {
    $value = $this->get('ourinsideimage')->value;
    if (!$value) {
      $value = gc('LocationOurInsideImage');
    }
    return $value;
  }

  public function getOurActiveText() {
    $value = $this->get('ouractivetext')->value;
    if (!$value) {
      $value = gc('LocationOurActiveText');
    }
    return $value;
  }

  public function getOurActiveImage() {
    $value = $this->get('ouractiveimage')->value;
    if (!$value) {
      $value = gc('LocationOurActiveImage');
    }
    return $value;
  }

  public function getOurOutdoorText() {
    $value = $this->get('ouroutdoortext')->value;
    if (!$value) {
      $value = gc('LocationOurOutdoorText');
    }
    return $value;
  }

  public function getOurOutdoorImage() {
    $value = $this->get('ouroutdoorimage')->value;
    if (!$value) {
      $value = gc('LocationOurOutdoorImage');
    }
    return $value;
  }
}
