<?php

namespace App\Models\Traits;

use App\Models\Block;
use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Rct567\DomQuery\DomQuery;

trait BlockRenderer
{

  public function addImageFilter(Block $block, $imageHTML)
  {
    if ($block->filter) {
      return '<figure class="radius-' . $block->radius . ' has-filter ' . $block->filter . '">' . $imageHTML . '</figure>';
    }
    return '<figure class="radius-' . $block->radius . ' ">' . $imageHTML . '</figure>';
  }

  protected function typography(Block $block, User $user, $dev = false)
  {
    $tag = $block->widget->name;
    if (trim(strip_tags($block->title)) == '') {
      if ($tag == 'h1') {
        $title = __('Heading 1');
      } else if ($tag == 'h2') {
        $title = __('Heading 2');
      } else if ($tag == 'h3') {
        $title = __('Heading 3 - Section name');
      } else if ($tag == 'h4') {
        $title = __('Heading 4');
      } else if ($tag == 'h5') {
        $title = __('Heading 5');
      } else if ($tag == 'h6') {
        $title = __('Heading 6');
      } else {
        $title = __('Paragraph');
      }
    } else {
      $title = $block->title;
    }
    $classes = $this->classes($block);
    $styles = $this->styles($block);
    $widgetHTML = '<' . $tag . ' name="' . $tag . '" class="widget ' . $tag . ' ' . $classes . '"  style="' . $styles . '">' . $title . '</' . $tag . '>';
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, $tag, $widgetHTML);
    }
    return $widgetHTML;
  }

  protected function list(Block $block, User $user, $dev = false)
  {

    // if (trim(strip_tags($block->title)) == '') {
    //   return '';
    // }
    if ($dev && trim($block->title) == '') {
      if ($block->widget->name == 'ul') {
        $block->title = __('Unordered list');
      } else {
        $block->title = __('Ordered list');
      }
    }
    $classes = $this->classes($block);
    $styles = $this->styles($block);
    $list = explode(PHP_EOL, $block->title);
    $items = '';
    $tag = $block->widget->name;
    $inc = 0;
    foreach ($list as $item) {
      $inc++;
      $items .= '<li data-order="' . $inc . '" class="' . $classes . '"  style="' . $styles . '">' . $item . '</li>';
    }
    $widgetHTML = '<' . $tag . ' name="' . $tag . '" class="widget ' . $tag . ' ' . $classes . '"  style="' . $styles . '">' . $items . '</' . $block->widget->name . '>';
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, $tag, $widgetHTML);
    }
    return $widgetHTML;
  }

  protected function fullname(Block $block, string $fullname = '', User $user, $dev = false)
  {
    $widgetTemplate = '<h1 class="widget fullname" name="fullname">{{fullname}}</h1>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }
    $fullname = trim($block->title) != '' ? $block->title : $fullname;
    if ($block->title == ' ' || $block->title == 'empty') {
      $fullname = '';
    }
    $fullname = collect(explode(' ', $fullname))
      ->map(fn($word) => "<span>$word</span>")
      ->implode(' ');
    $widgetHTML = str_replace('{{fullname}}', $fullname, $widgetTemplate);

    $widgetHTML = $this->addAttributes($block, 'fullname', $widgetHTML, $dev);
    return $widgetHTML;
  }

  protected function job_title(Block $block, string $job_title = '', User $user, $dev = false)
  {
    $widgetTemplate = '<h2 class="widget job_title" name="job_title">{{job_title}}</h2>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }
    $job_title = trim($block->title) != '' ? $block->title : $job_title;
    if ($block->title == ' ' || $block->title == 'empty') {
      $job_title = '';
    }
    $widgetHTML = str_replace('{{job_title}}', $job_title, $widgetTemplate);
    $widgetHTML = $this->addAttributes($block, 'job_title', $widgetHTML, $dev);
    return $widgetHTML;
  }

  protected function slogan(Block $block, string $slogan = '', User $user, $dev = false)
  {

    $widgetTemplate = '<h3 name="slogan" class="widget slogan">{{slogan}}</h3>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }
    $slogan = trim($block->title) != '' ? $block->title : $slogan;
    if ($block->title == ' ' || $block->title == 'empty') {
      $slogan = '';
    }
    $widgetHTML = str_replace('{{slogan}}', $slogan, $widgetTemplate);
    $widgetHTML = $this->addAttributes($block, 'slogan', $widgetHTML, $dev);
    return $widgetHTML;
  }

  protected function contacts(Block $block, string $contact = '', User $user, $dev = false)
  {

    $profile = $user->profile;

    $phone = $profile ? $profile->phone : '';
    $email = $profile ? $profile->email : '';
    $web = $profile ? $profile->web : '';
    $address = $profile ? $profile->address : '';
    $widgetTemplate = '<div class="widget contacts" name="contacts">
            <h3 id="contact_title">{{contact_title}}</h3>
            <a href="{{contact_url}}" style="color: inherit;text-decoration: none;" class="contact_item"><i>{{icon}}</i> {{contact_item}}</a>
          </div>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }
    $contact = trim($block->title) != '' ? $block->title : $contact;
    if ($block->title == ' ' || $block->title == 'empty') {
      $contact = '';
    }


    $widgetHTML = str_replace('{{contact_title}}', $contact, $widgetTemplate);
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, 'contacts', $widgetHTML);
    }
    $contactDOM = new DomQuery($widgetHTML);
    $contactTemplate = $contactDOM->find('.contact_item');
    $widgetHTML = $this->addAttributes($block, 'contacts', $widgetHTML, $dev);
    $contactHTML = '';

    if (trim($phone) != '') {
      $contactHTML .= str_replace('{{contact_item}}', $phone, $contactTemplate);
      $contactHTML = str_replace('{{icon}}', Blade::render('<x-icons.phone />'), $contactHTML);
      $contactHTML = str_replace('{{contact_url}}', 'tel:' . $phone, $contactHTML);
      $contactHTML = str_replace('%7B%7Bcontact_url%7D%7D', 'tel:' . $phone, $contactHTML);
    }

    if (trim($email) != '') {
      $contactHTML .= str_replace('{{contact_item}}', $email, $contactTemplate);
      $contactHTML = str_replace('{{icon}}', Blade::render('<x-icons.email />'), $contactHTML);
      $contactHTML = str_replace('{{contact_url}}', 'mailto:' . $email, $contactHTML);
      $contactHTML = str_replace('%7B%7Bcontact_url%7D%7D', 'mailto:' . $email, $contactHTML);
    }

    if (trim($web) != '') {
      $contactHTML .= str_replace('{{contact_item}}', $web, $contactTemplate);
      $contactHTML = str_replace('{{icon}}', Blade::render('<x-icons.web />'), $contactHTML);
      $contactHTML = str_replace('{{contact_url}}', $web, $contactHTML);
      $contactHTML = str_replace('%7B%7Bcontact_url%7D%7D', $web, $contactHTML);
    }

    if (trim($address) != '') {
      $contactHTML .= str_replace('{{contact_item}}', $address, $contactTemplate);
      $contactHTML = str_replace('{{icon}}', Blade::render('<x-icons.location />'), $contactHTML);
      $contactHTML = str_replace('{{contact_url}}', '', $contactHTML);
      $contactHTML = str_replace('%7B%7Bcontact_url%7D%7D', '', $contactHTML);

    }
    $contactDOM->find('.contact_item')->replaceWith('<div class="contacts">' . $contactHTML . '</div>')->getOuterHtml();
    $html = $contactDOM->getOuterHtml();
    $html = $this->addAttributes($block, 'contacts', $html);
    return $html;
    // return $widgetHTML;
  }

  protected function email(Block $block, string $email = '', User $user, $dev = false)
  {
    $color = $block->color ? $block->color : 'inherit';
    $classes = $this->classes($block);
    $widgetTemplate = '<a class="widget email ' . $classes . '" href={{email_link}} name="email" target="_blank" style="display:block;color: ' . $color . ';text-decoration: none;" name="widget email">{{email}}</a>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }
    $email = trim($block->title) != '' ? $block->title : $email;
    if ($block->title == ' ' || $block->title == 'empty') {
      $email = '';
    }
    $widgetHTML = str_replace('{{email}}', $email, $widgetTemplate);
    $widgetHTML = str_replace('{{email_link}}', 'mailto:' . $email, $widgetHTML);
    $widgetHTML = str_replace('%7B%7Bemail_link%7D%7D', 'mailto:' . $email, $widgetHTML);
    $widgetHTML = $this->addAttributes($block, 'email', $widgetHTML, $dev);
    return $widgetHTML;
  }

  protected function phone(Block $block, string $phone = '', User $user, $dev = false)
  {
    $color = $block->color ? $block->color : 'inherit';
    $classes = $this->classes($block);
    $widgetTemplate = '<a class="widget phone ' . $classes . '" href={{phone_link}} name="phone" target="_blank" style="display:block;color: ' . $color . ';text-decoration: none;" name="widget phone">{{phone}}</a>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }
    $phone = trim($block->title) != '' ? $block->title : $phone;
    if ($block->title == ' ' || $block->title == 'empty') {
      $phone = '';
    }
    $widgetHTML = str_replace('{{phone}}', $phone, $widgetTemplate);
    $widgetHTML = $this->addAttributes($block, 'phone', $widgetHTML, $dev);
    $widgetHTML = str_replace('{{phone_link}}', 'tel:' . $phone, $widgetHTML);
    $widgetHTML = str_replace('%7B%7Bphone_link%7D%7D', 'tel:' . $phone, $widgetHTML);
    return $widgetHTML;
  }

  protected function web(Block $block, string $web = '', User $user, $dev = false)
  {
    $color = $block->color ? $block->color : 'inherit';
    $classes = $this->classes($block);
    $widgetTemplate = '<a class="widget web ' . $classes . '" href={{web_link}} name="web" target="_blank" style="display:block;color: ' . $color . ';text-decoration: none;"  name="widget web">{{web}}</a>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }
    $web = trim($block->title) != '' ? $block->title : $web;
    if ($block->title == ' ' || $block->title == 'empty') {
      $web = '';
    }
    $widgetHTML = str_replace('{{web}}', $web, $widgetTemplate);
    $widgetHTML = $this->addAttributes($block, 'web', $widgetHTML, $dev);
    $widgetHTML = str_replace('{{web_link}}', $web, $widgetHTML);
    $widgetHTML = str_replace('%7B%7Bweb_link%7D%7D', $web, $widgetHTML);
    return $widgetHTML;
  }

  protected function address(Block $block, string $address = '', User $user, $dev = false)
  {
    $color = $block->color ? $block->color : 'inherit';
    $classes = $this->classes($block);
    $widgetTemplate = '<p class="widget address ' . $classes . '" name="address" name="widget address"  style="color: ' . $color . ';">{{address}}</p>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }
    $address = trim($block->title) != '' ? $block->title : $address;
    if ($block->title == ' ' || $block->title == 'empty') {
      $address = '';
    }
    $widgetHTML = str_replace('{{address}}', $address, $widgetTemplate);
    $widgetHTML = $this->addAttributes($block, 'address', $widgetHTML, $dev);
    return $widgetHTML;
  }

  protected function image(Block $block, string $image = '', User $user, $dev = false)
  {
    $widgetTemplate = '<div class="widget image" name="image">{{image}}</div>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }
    // $image = trim($block->title) != '' ? $block->title : $image;
    $radius = $block->radius ? $block->radius : '0';
    $blendMode = $block->blend ? $block->blend : 'normal';
    $image = $this->addImageFilter($block, '<img style="border-radius: ' . $radius . 'px;mix-blend-mode: ' . $blendMode . '" src="' . $image . '" />');
    $widgetHTML = str_replace('{{image}}', $image, $widgetTemplate);
    $widgetHTML = $this->addAttributes($block, 'image', $widgetHTML, $dev);
    return $widgetHTML;
  }

  protected function space(Block $block, User $user, $dev = false)
  {
    $widgetHTML = '<div class="widget space" name="space" style="height: ' . (int) $block->height . 'px">&nbsp;</div>';
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, 'space', $widgetHTML);
    }
    return $widgetHTML;
  }

  protected function about(Block $block, string $aboutContent = '', User $user, $dev = false)
  {
    $widgetTemplate = '<p class="widget" name="about">{{about}}</p>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }
    $about = trim($block->title) != '' ? $block->title : '';
    if ($block->title == ' ' || $block->title == 'empty') {
      $about = '';
    }
    $widgetHTML = str_replace('{{about_content}}', $aboutContent, $widgetTemplate);
    $widgetHTML = str_replace('{{about_title}}', $about, $widgetHTML);
    $widgetHTML = $this->addAttributes($block, 'about', $widgetHTML, $dev);
    return $widgetHTML;
  }

  protected function richtext(Block $block, $dev = false)
  {
    $widgetTemplate = '<div class="widget" name="richtext">{{richtext}}</div>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }
    $widgetHTML = str_replace('{{richtext}}', $block->title, $widgetTemplate);
    $widgetHTML = $this->addAttributes($block, 'richtext', $widgetHTML, $dev);
    return $widgetHTML;
  }

  protected function experiences(Block $block, string $title = '', User $user, $dev = false)
  {
    $widgetTemplate = '<div class="widget experiences" name="experiences">
            <h3 class="section_title" id="experiences_title">{{experiences_title}}</h3>
            <div class="experience_item">
              <h4 class="experience_position">{{experience_position}}</h4>
              <h5 class="experience_location">{{experience_location}} {{experience_years}}</h5>
              <p class="experience_about">{{experience_about}}</p>
            </div>
          </div>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }

    $workExperienceTitle = trim($block->title) != '' ? $block->title : $title;
    if ($block->title == ' ' || $block->title == 'empty') {
      $workExperienceTitle = '';
    }
    $widgetHTML = str_replace('{{experiences_title}}', $workExperienceTitle, $widgetTemplate);
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, 'experiences', $widgetHTML);
    }
    $experienceDOM = new DomQuery($widgetHTML);
    $experienceTemplate = $experienceDOM->find('.experience_item');
    $experienceHTMLs = '';
    $experiences = $user->experiences()->active()->orderBy('order')->get();
    foreach ($experiences as $key => $experience) {
      $inc = $key + 1;
      if ($block->from && $block->to) {
        if ($inc < (int) $block->from || $inc > (int) $block->to) {
          continue;
        }
      }
      $experienceHTML = str_replace('{{experience_position}}', $experience->position, $experienceTemplate);
      $experienceHTML = str_replace('{{experience_company}}', $experience->company, $experienceHTML);
      $experienceHTML = str_replace('{{experience_location}}', $experience->location, $experienceHTML);
      $experienceHTML = str_replace('{{experience_about}}', $experience->about, $experienceHTML);
      $experienceHTML = str_replace('{{experience_years}}', $experience->years($block->dateformat), $experienceHTML);
      $experienceHTML = str_replace('{{dateformat}}', $block->dateformat ? $block->dateformat : 'year', $experienceHTML);

      $experienceHTMLs .= $experienceHTML;
    }
    $experienceDOM->find('.experience_item')->replaceWith('<div class="experiences">' . $experienceHTMLs . '</div>')->getOuterHtml();

    return $experienceDOM->getOuterHtml();
  }

  protected function educations(Block $block, string $title = '', User $user, $dev = false)
  {
    $widgetTemplate = '<div class="educations widget" name="educations">
        <h3 class="section-title" id="educations_title">{{educations_title}}</h3>
        <div class="education_item">
          <h4 class="education_school">{{education_school}}</h4>
          <h4 class="education_degree">{{education_degree}}</h4>
          <h4 class="education_location">{{education_location}}</h4>
          <h5 class="education_field">{{education_field}}</h5>
          <p class="education_description">{{education_description}}</p>
          <span class="education_years">{{education_years}}</span>
        </div>
      </div>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }

    $educationTitle = trim($block->title) != '' ? $block->title : $title;
    if ($block->title == ' ' || $block->title == 'empty') {
      $educationTitle = '';
    }
    $widgetHTML = str_replace('{{educations_title}}', $educationTitle, $widgetTemplate);
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, 'educations', $widgetHTML);
    }
    $educationDOM = new DomQuery($widgetHTML);
    $educationTemplate = $educationDOM->find('.education_item');
    $educationHTMLs = '';
    $educations = $user->educations()->active()->orderBy('order')->get();
    foreach ($educations as $key => $education) {
      $inc = $key + 1;
      if ($block->from && $block->to) {
        if ($inc < (int) $block->from || $inc > (int) $block->to) {
          continue;
        }
      }
      $educationHTML = str_replace('{{education_school}}', $education->school, $educationTemplate);
      $educationHTML = str_replace('{{education_degree}}', $education->degree, $educationHTML);
      $educationHTML = str_replace('{{education_field}}', $education->field, $educationHTML);
      $educationHTML = str_replace('{{education_location}}', $education->location, $educationHTML);
      $educationHTML = str_replace('{{education_description}}', $education->description, $educationHTML);
      $educationHTML = str_replace('{{education_years}}', $education->years($block->dateformat), $educationHTML);
      $educationHTML = str_replace('{{dateformat}}', $block->dateformat ? $block->dateformat : 'year', $educationHTML);
      $educationHTMLs .= $educationHTML;
    }
    $educationDOM->find('.education_item')->replaceWith('<div class="educations">' . $educationHTMLs . '</div>')->getOuterHtml();

    return $educationDOM->getOuterHtml();
  }
  protected function skills(Block $block, string $title = '', User $user, $dev = false)
  {
    $widgetTemplate = '<div class="skills widget" name="skills">
        <h3 id="skills_title">{{skills_title}}</h3>
        <div class="skill_item">
          <div>{{skill_name}}</div>
          <div class="progress"><span style="display: block;border: solid 2px orange; width: {{skill_level}}%;">&nbsp;</span></div>
        </div>
      </div>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }

    $skillTitle = trim($block->title) != '' ? $block->title : $title;
    if ($block->title == ' ' || $block->title == 'empty') {
      $skillTitle = '';
    }
    $widgetHTML = str_replace('{{skills_title}}', $skillTitle, $widgetTemplate);
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, 'skills', $widgetHTML);
    }
    $skillDOM = new DomQuery($widgetHTML);
    $skillTemplate = $skillDOM->find('.skill_item');
    $skillHTMLs = '';
    $skills = $user->skills()->active()->orderBy('order')->get();
    foreach ($skills as $key => $skill) {
      $inc = $key + 1;
      if ($block->from && $block->to) {
        if ($inc < (int) $block->from || $inc > (int) $block->to) {
          continue;
        }
      }
      $skillHTML = str_replace('{{skill_name}}', $skill['name'], $skillTemplate);
      $skillHTML = str_replace('{{skill_level}}', $skill['level'], $skillHTML);
      $skillHTMLs .= $skillHTML;
    }
    $skillDOM->find('.skill_item')->replaceWith('<div class="skills">' . $skillHTMLs . '</div>')->getOuterHtml();

    return $skillDOM->getOuterHtml();
  }

  protected function languages(Block $block, string $title = '', User $user, $dev = false)
  {
    $widgetTemplate = '<div class="languages widget" name="languages">
        <h3 id="languages_title">{{languages_title}}</h3>
        <div class="language_item">
          <div>{{language_name}}</div>
          <div class="progress"><span style="display: block;border: solid 2px orange; width: {{language_level}}%;">&nbsp;</span></div>
        </div>
      </div>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }

    $languageTitle = trim($block->title) != '' ? $block->title : $title;
    if ($block->title == ' ' || $block->title == 'empty') {
      $languageTitle = '';
    }
    $widgetHTML = str_replace('{{languages_title}}', $languageTitle, $widgetTemplate);
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, 'languages', $widgetHTML);
    }
    $languageDOM = new DomQuery($widgetHTML);
    $languageTemplate = $languageDOM->find('.language_item');
    $languageHTMLs = '';
    $languages = $user->languages()->active()->orderBy('order')->get();
    foreach ($languages as $key => $language) {
      $inc = $key + 1;
      if ($block->from && $block->to) {
        if ($inc < (int) $block->from || $inc > (int) $block->to) {
          continue;
        }
      }
      $languageHTML = str_replace('{{language_name}}', $language['name'], $languageTemplate);
      $languageHTML = str_replace('{{language_level}}', $language['level'], $languageHTML);
      $languageHTMLs .= $languageHTML;
    }
    $languageDOM->find('.language_item')->replaceWith('<div class="languages">' . $languageHTMLs . '</div>')->getOuterHtml();

    return $languageDOM->getOuterHtml();
  }

  protected function projects(Block $block, string $title = '', User $user, $dev = false)
  {
    $widgetTemplate = '<div class="projects widget" name="projects">
        <h3 id="projects_title">{{projects_title}}</h3>
        <p class="project_item">{{project_name}}</p>
      </div>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }

    $projectTitle = trim($block->title) != '' ? $block->title : $title;
    if ($block->title == ' ' || $block->title == 'empty') {
      $projectTitle = '';
    }
    $widgetHTML = str_replace('{{projects_title}}', $projectTitle, $widgetTemplate);
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, 'projects', $widgetHTML);
    }
    $projectDOM = new DomQuery($widgetHTML);
    $projectTemplate = $projectDOM->find('.project_item');
    $projectHTMLs = '';
    $projects = $user->projects()->active()->orderBy('order')->get();
    foreach ($projects as $key => $project) {
      $inc = $key + 1;
      if ($block->from && $block->to) {
        if ($inc < (int) $block->from || $inc > (int) $block->to) {
          continue;
        }
      }
      $projectHTML = str_replace('{{project_name}}', $project->name, $projectTemplate);
      $projectHTML = str_replace('{{project_description}}', $project->description, $projectHTML);
      $projectHTML = str_replace('{{project_year}}', $project->year($block->dateformat), $projectHTML);
      $projectHTML = str_replace('{{project_month}}', $project->month(), $projectHTML);
      $projectHTML = str_replace('{{project_url}}', $project->url, $projectHTML);
      $projectHTML = str_replace('%7B%7Bproject_url%7D%7D', $project->url, $projectHTML);
      $projectHTML = str_replace('{{dateformat}}', $block->dateformat ? $block->dateformat : 'year', $projectHTML);


      $projectHTMLs .= $projectHTML;
    }
    $projectDOM->find('.project_item')->replaceWith('<div class="projects">' . $projectHTMLs . '</div>')->getOuterHtml();

    return $projectDOM->getOuterHtml();
  }

  protected function certifications(Block $block, string $title = '', User $user, $dev = false)
  {
    $widgetTemplate = '<div class="certificates widget" name="certificates">
        <h3 id="certificates_title" class="font-medium">{{certificates_title}}</h3>
        <div class="certificate_item">
          <h4><span>{{certificate_name}}</span></h4>
          <h5 class="has-children dash"><span class="certificate_organization">{{certificate_organization}}</span><span class="certificate_year">{{certificate_year}}</span></h5>
          <p class="certificate_description">{{certificate_description}}</p>
          <div style="height: 5px"></div>
        </div>
      </div>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }

    $certificationTitle = trim($block->title) != '' ? $block->title : $title;
    if ($block->title == ' ' || $block->title == 'empty') {
      $certificationTitle = '';
    }
    $widgetHTML = str_replace('{{certificates_title}}', $certificationTitle, $widgetTemplate);
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, 'certificates', $widgetHTML);
    }
    $certificationDOM = new DomQuery($widgetHTML);
    $certificationTemplate = $certificationDOM->find('.certificate_item');
    $certificationHTMLs = '';
    $certifications = $user->certifications()->active()->orderBy('order')->get();
    foreach ($certifications as $key => $certification) {
      $inc = $key + 1;
      if ($block->from && $block->to) {
        if ($inc < (int) $block->from || $inc > (int) $block->to) {
          continue;
        }
      }
      $certificationHTML = str_replace('{{certificate_name}}', $certification->name, $certificationTemplate);
      $certificationHTML = str_replace('{{certificate_organization}}', $certification->organization, $certificationHTML);
      $certificationHTML = str_replace('{{certificate_year}}', $certification->year($block->dateformat), $certificationHTML);
      $certificationHTML = str_replace('{{certificate_month}}', $certification->month(), $certificationHTML);
      $certificationHTML = str_replace('{{certificate_description}}', $certification->description, $certificationHTML);
      $certificationHTML = str_replace('{{dateformat}}', $block->dateformat ? $block->dateformat : 'year', $certificationHTML);
      if ($certification->year($block->dateformat) == '') {
        $tempDom = new DomQuery($certificationHTML);
        $tempDom->find('.certificate_year_month')->remove();
        $certificationHTML = $tempDom->getOuterHtml();
      }
      $certificationHTMLs .= $certificationHTML;
    }
    $certificationDOM->find('.certificate_item')->replaceWith('<div class="certificates">' . $certificationHTMLs . '</div>')->getOuterHtml();

    return $certificationDOM->getOuterHtml();
  }

  protected function awards(Block $block, string $title = '', User $user, $dev = false)
  {
    $widgetTemplate = '<div class="widget awards" name="awards">
          <h3 id="awards_title">{{awards_title}}</h3>
          <div class="award_item">
            <h4 class="award_title">{{award_name}}</h4>
            <div class="award_organization"><em>{{award_organization}}</em></div>
            <div class="award_year">{{award_year}}</div>
              <p class="award_description">{{award_description}}</p>
          </div>
        </div>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }

    $awardTitle = trim($block->title) != '' ? $block->title : $title;
    if ($block->title == ' ' || $block->title == 'empty') {
      $awardTitle = '';
    }
    $widgetHTML = str_replace('{{awards_title}}', $awardTitle, $widgetTemplate);
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, 'awards', $widgetHTML);
    }
    $awardDOM = new DomQuery($widgetHTML);
    $awardTemplate = $awardDOM->find('.award_item');
    $awardHTMLs = '';
    $awards = $user->awards()->active()->orderBy('order')->get();
    foreach ($awards as $key => $award) {
      $inc = $key + 1;
      if ($block->from && $block->to) {
        if ($inc < (int) $block->from || $inc > (int) $block->to) {
          continue;
        }
      }
      // $year = $award->date ? $award->date->format("Y") : '';
      $awardHTML = str_replace('{{award_name}}', $award->name, $awardTemplate);
      $awardHTML = str_replace('{{award_year}}', $award->year($block->dateformat), $awardHTML);
      $awardHTML = str_replace('{{award_organization}}', $award->organization, $awardHTML);
      $awardHTML = str_replace('{{award_description}}', $award->description, $awardHTML);
      $awardHTML = str_replace('{{dateformat}}', $block->dateformat ? $block->dateformat : 'year', $awardHTML);
      $awardHTMLs .= $awardHTML;
    }
    $awardDOM->find('.award_item')->replaceWith('<div class="awards">' . $awardHTMLs . '</div>')->getOuterHtml();

    return $awardDOM->getOuterHtml();
  }

  protected function socials(Block $block, string $title = '', User $user, $dev = false)
  {
    $widgetTemplate = '<div class="widget socials" name="socials">
            <h3 id="socials_title">{{socials_title}}</h3>
            <a href="{{social_url}}" target="_blank" style="color: inherit;text-decoration: none;" class="social_item">
              <i>{{icon}}</i>
              <div>
                <span>{{social_name}}</span>
                <span class="social_item_handle">{{social_handle}}</span>
              </div>
            </a>
          </div>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }

    $socialTitle = $block->title != '' ? $block->title : $title;
    if ($block->title == ' ' || $block->title == 'empty') {
      $socialTitle = '';
    }
    $widgetHTML = str_replace('{{socials_title}}', $socialTitle, $widgetTemplate);
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, 'socials', $widgetHTML);
    }
    $socialDOM = new DomQuery($widgetHTML);
    $socialTemplate = $socialDOM->find('.social_item');
    $socialHTMLs = '';
    $socials = $user->socials()->active()->orderBy('order')->get();
    foreach ($socials as $key => $social) {
      $inc = $key + 1;
      if ($block->from && $block->to) {
        if ($inc < (int) $block->from || $inc > (int) $block->to) {
          continue;
        }
      }
      $socialHTML = str_replace('{{icon}}', Blade::render('<x-socials.' . $social->site . ' />'), $socialTemplate);
      $socialHTML = str_replace('{{social_handle}}', $social->handle, $socialHTML);
      $socialHTML = str_replace('{{social_site}}', $social->site, $socialHTML);
      $socialHTML = str_replace('{{social_name}}', $social->name, $socialHTML);
      $socialHTML = str_replace('{{social_url}}', $social->url, $socialHTML);
      $socialHTML = str_replace('%7B%7Bsocial_url%7D%7D', $social->url, $socialHTML);

      if ($social->url != '') {

      }
      $socialHTMLs .= $socialHTML;
    }
    $socialDOM->find('.social_item')->replaceWith('<div class="socials">' . $socialHTMLs . '</div>')->getOuterHtml();

    return $socialDOM->getOuterHtml();
  }

  protected function hobbies(Block $block, string $title = '', User $user, $dev = false)
  {
    $widgetTemplate = '<div class="widget hobbies" name="hobbies">
          <h3 class="hobbies_title">{{hobbies_title}}</h3>
            <div class="hobby_item">{{icon}} <span>{{hobby_title}}</span>
          </div>
        </div>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }

    $hobbyTitle = $block->title != '' ? $block->title : $title;
    if ($block->title == ' ' || $block->title == 'empty') {
      $hobbyTitle = '';
    }
    $widgetHTML = str_replace('{{hobbies_title}}', $hobbyTitle, $widgetTemplate);
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, 'hobbies', $widgetHTML);
    }
    $hobbyDOM = new DomQuery($widgetHTML);
    $hobbyTemplate = $hobbyDOM->find('.hobby_item');
    $hobbyHTMLs = '';
    $hobbies = $user->hobbies()->active()->orderBy('order')->get();
    foreach ($hobbies as $key => $hobby) {
      $inc = $key + 1;
      if ($block->from && $block->to) {
        if ($inc < (int) $block->from || $inc > (int) $block->to) {
          continue;
        }
      }
      // $hobbyHTML = str_replace('{{ic/on}}', $this->icon($hobby['site']), $hobbyTemplate);
      $hobbyHTML = str_replace('{{hobby_title}}', $hobby->name, $hobbyTemplate);
      if ($hobby->icon) {
        $hobbyHTML = str_replace('{{icon}}', Blade::render('<x-hobbies.' . $hobby->icon . ' />'), $hobbyHTML);
        $hobbyHTMLs .= $hobbyHTML;
      }
    }
    $hobbyDOM->find('.hobby_item')->replaceWith('<div class="hobbies">' . $hobbyHTMLs . '</div>')->getOuterHtml();

    return $hobbyDOM->getOuterHtml();
  }

  protected function references(Block $block, string $title = '', User $user, $dev = false)
  {
    $widgetTemplate = '<div class="widget references" name="references">
          <h3 class="references_title">{{references_title}}</h3>
            <div class="reference_item">
              <div class="reference_name">{{reference_name}}</div>
              <div class="reference_job_title">{{reference_position}}</div>
              <div class="reference_job_phone">{{phone_icon}}</span>{{reference_phone}}</div>
              <div class="reference_job_email">{{email_icon}}</span>{{reference_email}}</div>
            </div>
        </div>';
    if (isset($this->widgetTemplates[$block->widget->name])) {
      $widgetTemplate = $this->widgetTemplates[$block->widget->name];
    }

    $referenceTitle = trim($block->title) != '' ? $block->title : $title;
    if ($block->title == ' ' || $block->title == 'empty') {
      $referenceTitle = '';
    }
    $widgetHTML = str_replace('{{references_title}}', $referenceTitle, $widgetTemplate);
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, 'references', $widgetHTML);
    }
    $referenceDOM = new DomQuery($widgetHTML);
    $referenceTemplate = $referenceDOM->find('.reference_item');
    $referenceHTMLs = '';
    $references = $user->references()->active()->orderBy('order')->get();
    foreach ($references as $key => $reference) {
      $inc = $key + 1;
      if ($block->from && $block->to) {
        if ($inc < (int) $block->from || $inc > (int) $block->to) {
          continue;
        }
      }
      // $referenceHTML = str_replace('{{ic/on}}', $this->icon($reference['site']), $referenceTemplate);
      $referenceHTML = str_replace('{{reference_name}}', $reference->name, $referenceTemplate);
      $referenceHTML = str_replace('{{reference_phone}}', $reference->phone, $referenceHTML);
      $referenceHTML = str_replace('{{reference_email}}', $reference->email, $referenceHTML);
      $referenceHTML = str_replace('{{reference_company}}', $reference->company, $referenceHTML);
      $referenceHTML = str_replace('{{reference_position}}', $reference->position, $referenceHTML);
      if ($reference->phone) {
        $referenceHTML = str_replace('{{phone_icon}}', Blade::render('<x-icons.phone />'), $referenceHTML);
      } else {
        $referenceHTML = str_replace('{{phone_icon}}', '', $referenceHTML);
      }
      if ($reference->email) {
        $referenceHTML = str_replace('{{email_icon}}', Blade::render('<x-icons.email />'), $referenceHTML);
      } else {
        $referenceHTML = str_replace('{{email_icon}}', '', $referenceHTML);
      }
      if ($reference->icon) {
        // $referenceHTML = str_replace('{{icon}}', Blade::render('<x-.' . $reference->icon . ' />'), $referenceHTML);
      }
      $referenceHTMLs .= $referenceHTML;
    }
    $referenceDOM->find('.reference_item')->replaceWith('<div class="references">' . $referenceHTMLs . '</div>')->getOuterHtml();

    return $referenceDOM->getOuterHtml();
  }




}