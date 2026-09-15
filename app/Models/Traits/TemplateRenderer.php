<?php

namespace App\Models\Traits;

use App\Models\Block;
use App\Models\Resume;
use App\Models\User;
use Rct567\DomQuery\DomQuery;

trait TemplateRenderer
{

  public function formattedColors()
  {
    return implode(',', explode(',', trim(trim(strtoupper($this->colors), ','))));
  }

  protected $folderPath = '';
  protected $html = '';
  protected $dom = null;
  protected $widgetTemplates = [];
  protected $resume = null;

  public function setDOM($dom = null)
  {
    $this->dom = $dom;
  }

  protected function embedCSSFiles()
  {
    // 'screen.css' => 'screen',
    // 'print.css' => 'print',
    $cssContents = '';
    $cssFiles = [
      'all.css' => 'all',
    ];
    foreach ($cssFiles as $fileName => $mediaName) {
      $cssContents .= $this->embedCSSFile($fileName, $mediaName);
    }
    return $cssContents;
  }

  public function allCSSTags()
  {
    $this->folderPath = resource_path('templates/' . $this->folder);
    $tags = $this->embedGlobalCSSFiles('canvas');
    $tags .= $this->embedCSSFiles();
    return $tags;
  }

  protected function embedGlobalCSSFiles($context = 'pdf')
  {
    $globalCSSFilesPath = resource_path('template_css');
    $printCSSFilePath = $globalCSSFilesPath . '/print.css';
    $screenCSSFilePath = $globalCSSFilesPath . '/screen.css';
    $content = '';
    if (is_file($printCSSFilePath)) {
      $printCSSFileContent = file_get_contents($printCSSFilePath);
      $content .= '<style media="print">' . $printCSSFileContent . '</style>';
    }
    if ($context == 'canvas') {
      if (is_file($screenCSSFilePath)) {
        $screenCSSFileContent = file_get_contents($screenCSSFilePath);
        $content .= '<style media="screen">' . $screenCSSFileContent . '</style>';
      }
    }
    return $content;
  }

  protected function embedCSSFile($filename, $media)
  {

    $cssFilePath = $this->folderPath . '/css/' . $filename;
    if (file_exists($cssFilePath)) {
      $cssContent = file_get_contents($cssFilePath);
      return '<style media="' . $media . '">' . $cssContent . '</style>';
    }
    return '';
  }

  public function getWidgetTemplates()
  {
    $widgets = $this->dom->find('.widget');
    foreach ($widgets as $widget) {
      $name = $widget->attr('name');
      $this->widgetTemplates[$name] = $widget->getOuterHtml();
    }
  }
  public function renderResume(Resume $resume, int $page, $context = 'pdf', $style = 'default')
  {
    if (auth()->check()) {
      if (!auth()->user()->profile) {
        auth()->user()->initializeAccount();
      }
    }
    app()->setLocale($resume->user->language);
    $this->resume = $resume;
    $this->folderPath = resource_path('templates/' . $this->folder);
    $templatePath = $this->folderPath . '/index.html';
    $cssContents = $this->embedCSSFiles();
    if (!file_exists($templatePath)) {
      return 'Template file not found!';
    }

    $globalCSSContent = $this->embedGlobalCSSFiles($context);
    $htmlContent = file_get_contents($templatePath);
    $this->html = str_replace('<style></style>', $cssContents, $htmlContent);
    $this->html = str_replace('<style id="global"></style>', $globalCSSContent, $this->html);
    $this->html = str_replace('{{page}}', $page, $this->html);
    $this->html = str_replace('{{canvas}}', $context, $this->html);
    $this->html = str_replace('{{lang}}', $resume->user->language, $this->html);
    // if (auth()->check() && auth()->user()->isAdmin()) {
    //   // $this->html = str_replace('{{style}}', 'dark', $this->html);
    //   // $this->html = str_replace('{{style}}', 'blue', $this->html);
    //   // $this->html = str_replace('{{style}}', 'green', $this->html);
    //   // $this->html = str_replace('{{style}}', 'cream', $this->html);
    //   // $this->html = str_replace('{{style}}', 'coffee', $this->html);
    //   $this->html = str_replace('{{style}}', 'tennis', $this->html);
    // }
    $this->html = str_replace('{{style}}', $style, $this->html);

    $this->dom = new DomQuery($this->html);
    $this->getWidgetTemplates();
    $this->renderSections($page, $resume->user);
    return '<!DOCTYPE html>' . $this->dom->getOuterHtml();
  }

  public function renderSections(int $page, User $user)
  {

    $sections = $this->dom->find('.section');
    foreach ($sections as $section) {
      $name = $section->attr('name');
      $blocksHTML = $this->renderBlocks($name, $page, $user);
      if ($blocksHTML != '') {
        $section->html($blocksHTML);
      } else {
        $section->remove();
      }
    }
  }


  public function renderBlocks($sectionName, int $page, User $user)
  {
    $blocksHTML = '';
    $blocks = $this->resume->blocks()->where('resume_page', $page)->orderBy('order')->get();
    foreach ($blocks as $key => $block) {
      if ($block->section == $sectionName) {
        if ($block->active) {
          $blocksHTML .= $this->renderBlock($block, $user);
        }
      }
    }
    return $blocksHTML;
  }

  protected function classes(Block $block)
  {
    $classes = [];

    if ($block->bold) {
      $classes[] = 'bold';
    }
    if ($block->italic) {
      $classes[] = 'italic';
    }
    if ($block->underline) {
      $classes[] = 'underline';
    }
    if ($block->strikethrough) {
      $classes[] = 'strikethrough';
    }
    if ($block->uppercase) {
      $classes[] = 'uppercase';
    }
    if ($block->align) {
      $classes[] = 'align-' . $block->align;
    }
    return implode(' ', $classes);
  }

  public function styles(Block $block, bool $asArray = false)
  {
    if ($asArray) {
      $styles = [];
      if ($block->color != '') {
        $styles[] = 'color: ' . $block->color . ';';
      }
      return $styles;
    } else {

      $styles = '';
      if ($block->color != '') {
        $styles .= 'color: ' . $block->color . ';';
      }

      return $styles;
    }
  }

  public function devAttributes(Block $block, $widgetName, $widgetHTML)
  {
    $widgetTitle = __($block->widget->title);
    $dom = new DomQuery($widgetHTML);
    $widget = $dom->find('[name="' . $widgetName . '"]');
    if (!$block->active) {
      $widget->addClass('block-invisible');
    }
    if (count($widget)) {
      $widget->attr('blockId', $block->id);
      $widget->attr('widget-title', $widgetTitle);
      $widget->append('<b class="delete-block-button"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg></b>');
      // <button class="block-settings-button"></button>
      return $widget->getOuterHtml();
    }


    return $widgetHTML;
  }
  public function addClasses(Block $block, $widgetName, $widgetHTML)
  {
    $dom = new DomQuery($widgetHTML);
    $widget = $dom->find('[name="' . $widgetName . '"]');
    if (count($widget)) {
      $widget->addClass($this->classes($block));
      return $widget->getOuterHtml();
    }
    return $widgetHTML;
  }
  public function addStyles(Block $block, $widgetName, $widgetHTML)
  {
    if (trim($widgetHTML) == '') {
      return '';
    }
    $dom = new DomQuery($widgetHTML);
    $widget = $dom->find('[name="' . $widgetName . '"]');
    if (count($widget)) {
      $widget->attr('style', $this->styles($block, false));
      $span = $widget->find('span');
      if (count($span)) {
        $span->attr('style', $this->styles($block, false));
      }
      $p = $widget->find('p');
      if (count($p)) {
        $p->attr('style', $this->styles($block, false));
      }
      return $widget->getOuterHtml();
    }
    return $widgetHTML;
  }
  public function addAttributes(Block $block, $widgetName, $widgetHTML, $dev = false)
  {
    $widgetHTML = $this->addClasses($block, $widgetName, $widgetHTML);
    $widgetHTML = $this->addStyles($block, $widgetName, $widgetHTML);
    if ($dev) {
      $widgetHTML = $this->devAttributes($block, $widgetName, $widgetHTML);
    }
    return $widgetHTML;
  }



  public function renderBlock(Block $block, User $user, $dev = false)
  {
    if (!$user->profile) {
      return __('Profile information not found');
    }
    $profile = $user->profile;
    $fullname = $profile && $profile->fullname ? $profile->fullname : '';
    $job_title = $profile && $profile->job_title ? $profile->job_title : '';
    $image = $profile->image_base64_url($block->filter);
    $slogan = $profile && $profile->slogan ? $profile->slogan : '';
    $about = $profile && $profile->about ? $profile->about : '';
    $phone = $profile && $profile->phone ? $profile->phone : '';
    $email = $profile && $profile->email ? $profile->email : '';
    $web = $profile && $profile->web ? $profile->web : '';
    $address = $profile && $profile->address ? $profile->address : '';

    if (in_array($block->widget->name, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'])) {
      return $this->typography($block, $user, $dev);
    }

    if ($block->widget->name == 'ol' || $block->widget->name == 'ul') {
      return $this->list($block, $user, $dev);
    }

    if ($block->widget->name == 'fullname') {
      return $this->fullname($block, $fullname, $user, $dev);
    }

    if ($block->widget->name == 'job_title') {
      return $this->job_title($block, $job_title, $user, $dev);
    }

    if ($block->widget->name == 'slogan') {
      return $this->slogan($block, $slogan, $user, $dev);
    }

    if ($block->widget->name == 'email') {
      return $this->email($block, $email, $user, $dev);
    }

    if ($block->widget->name == 'phone') {
      return $this->phone($block, $phone, $user, $dev);
    }

    if ($block->widget->name == 'web') {
      return $this->web($block, $web, $user, $dev);
    }

    if ($block->widget->name == 'address') {
      return $this->address($block, $address, $user, $dev);
    }

    if ($block->widget->name == 'image') {
      return $this->image($block, $image, $user, $dev);
    }

    if ($block->widget->name == 'space') {
      return $this->space($block, $user, $dev);
    }

    if ($block->widget->name == 'about') {
      return $this->about($block, $about, $user, $dev);
    }

    if ($block->widget->name == 'richtext') {
      return $this->richtext($block, $dev);
    }

    if ($block->widget->name == 'contacts') {
      return $this->contacts($block, __('Contact'), $user, $dev);
    }

    if ($block->widget->name == 'experiences') {
      return $this->experiences($block, __('Work experience'), $user, $dev);
    }

    if ($block->widget->name == 'educations') {
      return $this->educations($block, __('Educations'), $user, $dev);
    }

    if ($block->widget->name == 'skills') {
      return $this->skills($block, __('Skills'), $user, $dev);
    }

    if ($block->widget->name == 'languages') {
      return $this->languages($block, __('Languages'), $user, $dev);
    }

    if ($block->widget->name == 'projects') {
      return $this->projects($block, __('Projects'), $user, $dev);
    }

    if ($block->widget->name == 'certificates') {
      return $this->certifications($block, __('Certificates'), $user, $dev);
    }

    if ($block->widget->name == 'awards') {
      return $this->awards($block, __('Awards'), $user, $dev);
    }

    if ($block->widget->name == 'socials') {
      return $this->socials($block, __('Socials'), $user, $dev);
    }

    if ($block->widget->name == 'hobbies') {
      return $this->hobbies($block, __('Hobbies'), $user, $dev);
    }

    if ($block->widget->name == 'references') {
      return $this->references($block, __('References'), $user, $dev);
    }

    // return '<div>' . $block->widget->title . '</div>';
    return '<span>' . '</span>';
  }

}