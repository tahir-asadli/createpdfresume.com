<?php

namespace App\Models\Traits;

use App\Models\Block;
use App\Models\Experience;
use App\Models\Plan;
use App\Models\Resume;
use App\Models\Skill;
use App\Models\Template;
use App\Models\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

trait HasInfo
{
  public $job_title = '';
  public function initializeAccount()
  {
    $plan = Plan::where('slug', 'free')->first();
    $this->addSkills();
    $this->addLanguages();
    $this->addSocials();
    $this->addAwards();
    $this->addHobbies();
    $this->addCertifications();
    $this->addExperiences();
    $this->addProfile();
    $this->addEducations();
    $this->addProjects();
    $this->addReferences();
    // $resume = $this->addResume();
    // if ($resume) {
    //   $this->addBlocks($resume);
    // }
  }

  public function addReferences()
  {
    $jobTitles = [
      'Software Engineer',
      'Marketing Manager',
      'Registered Nurse',
      'Financial Analyst',
      'Elementary School Teacher',
      'Project Coordinator',
      'Graphic Designer',
      'Customer Service Representative',
      'Data Scientist',
      'Human Resources Specialist',
    ];
    $jobTitles = shuffle_assoc($jobTitles);
    $referenceIndex = 0;
    foreach ($jobTitles as $key => $value) {


      if ($referenceIndex < 4) {
        $this->references()->create(
          [
            'name' => fake()->name(),
            'company' => fake()->company(),
            'position' => $value,
            'email' => fake()->email(),
            'phone' => fake()->PhoneNumber(),
            'active' => true
          ]
        );
      }
      $referenceIndex++;
    }
  }

  public function addProjects()
  {

    $projects = [
      'Smart Home Energy Management System',
      'Community Garden Revitalization Initiative',
      'E-commerce Platform for Handmade Crafts',
      'Analysis of Renewable Energy Integration in Urban Grids',
      'Mobile Application for Local Event Discovery',
      'Historic Building Preservation and Renovation',
      'Developing a Gamified Learning Module for Mathematics',
      'Supply Chain Optimization for Perishable Goods',
      'Interactive Data Visualization Dashboard for Public Health Trends',
      'Sustainable Water Harvesting System for Arid Regions',
    ];
    $projects = shuffle_assoc($projects);
    $date = 2010;
    $projectIndex = 0;

    foreach ($projects as $project) {
      $date += 2;
      if ($projectIndex < 3) {
        $this->projects()->create(
          [
            'name' => $project,
            'description' => fake()->paragraph(),
            'date' => Carbon::parse("15-01-{$date}"),
            'active' => 1,
          ]
        );
      }
      $projectIndex++;
    }
  }

  public function addEducations()
  {
    $degrees = [
      'Bachelor',
      'Master',
      'Doctorate',
    ];
    $degrees = shuffle_assoc($degrees);
    $colleges = [
      [
        'name' => 'University of Oxford',
        'location' => 'United Kingdom',
      ],
      [
        'name' => 'Massachusetts Institute of Technology',
        'location' => 'United States',
      ],
      [
        'name' => 'Harvard University',
        'location' => 'United States',
      ],
      [
        'name' => 'Stanford University',
        'location' => 'United States',
      ],
      [
        'name' => 'University of Cambridge',
        'location' => 'United Kingdom',
      ],
      [
        'name' => 'National University of Singapore',
        'location' => 'Singapore',
      ],
      [
        'name' => 'Peking University',
        'location' => 'China',
      ],
      [
        'name' => 'University of Tokyo',
        'location' => 'Japan',
      ],
      [
        'name' => 'University of Cape Town',
        'location' => 'South Africa',
      ],
      [
        'name' => 'University of Toronto',
        'location' => 'Canada',
      ],
      [
        'name' => 'University of Melbourne',
        'location' => 'Australia',
      ],
      [
        'name' => 'University of Sydney',
        'location' => 'Australia',
      ],
    ];
    $colleges = shuffle_assoc($colleges);

    $fields = [
      'Faculty of Arts and Humanities',
      'Faculty of Sciences',
      'Faculty of Engineering',
      'Faculty of Business and Economics',
      'Faculty of Medicine',
      'Faculty of Law',
      'Faculty of Education',
      'Faculty of Social Sciences',
      'Faculty of Environmental Studies',
      'Faculty of Information Technology/Computer Science',
    ];
    $fields = shuffle_assoc($fields);
    $startDate = 2010;
    $educationIndex = 0;
    foreach ($degrees as $k => $degree) {
      $endDate = $startDate + 2;
      $college = $colleges[$k];
      $field = $fields[$k];

      if ($educationIndex < 2) {
        $this->educations()->create(
          [
            'school' => $college['name'],
            'degree' => $degree,
            'field' => $field,
            'description' => fake()->paragraph(),
            'location' => $college['location'],
            'start_date' => Carbon::parse("15-01-{$startDate}"),
            'end_date' => Carbon::parse("20-12-{$endDate}"),
            'active' => 1,
          ]
        );
      }
      $educationIndex++;
      $startDate += 2;
    }
  }

  public function addExperiences()
  {
    global $positions;
    $companies = [
      'Stripe',
      'Unity Technologies',
      'Impossible Foods',
      'Palantir Technologies',
      'Toast, Inc.',
      'Atlassian',
      'ServiceNow',
      'DocuSign',
      'Nio Inc.',
      'AstraZeneca',
      'Spotify Technology S.A.',
      'Square Enix Holdings Co., Ltd.',
      'ASML Holding N.V.',
      'Shopify Inc.',
      'GitLab Inc.',
      'Wise',
      'Klarna Bank AB',
      'Roku Inc.',
      'Veeam Software',
      'UiPath Inc.',
    ];
    $companies = shuffle_assoc($companies);
    $positions = [
      'Account Executive',
      'Administrative Assistant',
      'Business Development Manager',
      'Chief Executive Officer (CEO)',
      'Content Creator',
      'Customer Success Manager',
      'Data Analyst',
      'Digital Marketing Specialist',
      'Director of Operations',
      'Financial Advisor',
      'Human Resources Generalist',
      'IT Support Specialist',
      'Legal Counsel',
      'Marketing Coordinator',
      'Operations Manager',
      'Product Manager',
      'Research Scientist',
      'Sales Representative',
      'Senior Accountant',
      'Software Developer',
    ];
    shuffle($positions);
    $this->job_title = $positions[0];
    $startDate = 2010;
    $companyIndex = 0;
    foreach ($companies as $key => $company) {
      $endDate = $startDate + 2;
      $position = $positions[$key];

      if ($companyIndex < 4) {
        $this->experiences()->create(
          [
            'company' => $company,
            'position' => $position,
            'about' => fake()->paragraph(),
            'location' => fake()->streetAddress(),
            'start_date' => Carbon::parse("15-01-{$startDate}"),
            'end_date' => Carbon::parse("20-12-{$endDate}"),
            'active' => true,
          ]
        );
      }
      $companyIndex++;
      $startDate += 2;
    }
  }

  private function addCertifications()
  {

    $certifications = [
      'Amazon Web Services (AWS)' => 'AWS Certified Solutions Architect - Associate',
      'Scrum Alliance' => 'Certified ScrumMaster (CSM)',
      'Google' => 'Google Ads Search Certification',
      'Microsoft' => 'Microsoft Certified: Azure Administrator Associate',
      'American Institute of Certified Public Accountants (AICPA)' => 'Certified Public Accountant (CPA)',
      'Project Management Institute (PMI)' => 'Project Management Professional (PMP)',
      'Cisco Systems' => 'Cisco Certified Network Associate (CCNA)',
      'CompTIA' => 'CompTIA Security+',
      '(ISC)²' => 'Certified Information Systems Security Professional (CISSP)',
      'HubSpot Academy' => 'HubSpot Inbound Marketing Certification',
      'Oracle' => 'Oracle Certified Professional, Java SE Programmer',
      'Certified Financial Planner Board of Standards, Inc.' => 'Certified Financial Planner (CFP)',
      'Various, often ASQ (American Society for Quality) or IASSC' => 'Six Sigma Green Belt',
      'Salesforce' => 'Salesforce Certified Administrator',
      'HR Certification Institute (HRCI)' => 'Professional in Human Resources (PHR)',
      'EC-Council' => 'Certified Ethical Hacker (CEH)',
      'Cloud Native Computing Foundation (CNCF)' => 'Certified Kubernetes Administrator (CKA)',
      'Tableau (a Salesforce Company)' => 'Tableau Desktop Specialist',
    ];
    $certifications = shuffle_assoc($certifications);

    $date = 2010;
    $certificationIndex = 0;
    foreach ($certifications as $organization => $certification) {
      $date += 2;
      if ($certificationIndex < 3) {
        $this->certifications()->create(
          [
            'name' => $certification,
            'organization' => $organization,
            'description' => fake()->paragraph(),
            'date' => Carbon::parse("01-01-{$date}"),
            'active' => true,
          ]
        );
      }
      $certificationIndex++;
    }

  }

  private function addHobbies()
  {
    $hobbies = [
      'Drawing' => 'drawing',
      'Painting' => 'painting',
      'Photography' => 'photography',
      'Music' => 'music',
      'Crafting' => 'crafting',
      'Graphic Design' => 'graphic_design',
      'Hiking' => 'hiking',
      'Camping' => 'camping',
      'Cycling' => 'cycling',
      'Swimming' => 'swimming',
      'Gardening' => 'gardening',
      'Fishing' => 'fishing',
      'Climbing' => 'climbing',
      'Running' => 'running',
      'Martial Arts' => 'martial_arts',
      'Yoga' => 'yoga',
      'Meditation' => 'meditation',
      'Coding' => 'coding',
    ];
    $hobbies = shuffle_assoc($hobbies);
    $hobbyIndex = 0;
    foreach ($hobbies as $key => $value) {
      if ($hobbyIndex < 4) {
        $this->hobbies()->create(
          [
            'name' => $key,
            'icon' => str_replace(' ', '_', strtolower($value)),
            'active' => true,
          ]
        );
      }
      $hobbyIndex++;
    }
  }

  private function addSocials()
  {
    $socials = ['x', 'facebook', 'linkedin', 'instagram'];
    foreach ($socials as $social) {
      $name = Str::slug($this->name);
      $handle = '@' . $name;
      $this->socials()->create(
        [
          'site' => $social,
          'name' => ucfirst($social),
          'url' => "https://www.{$social}.com/$name",
          'handle' => $handle,
          'active' => true,
        ]
      );
    }
  }


  private function addAwards()
  {

    $awards = [
      'Employee of the Month/Year Award' =>
        [
          'organization' => 'Stripe',
          'description' => 'Recognizes outstanding workplace performance.',
        ],
      'Teamwork Award' =>
        [
          'organization' => 'Unity Technologies',
          'description' => 'Celebrates excellent collaboration within a group.',
        ],
      'Customer Service Excellence Award' =>
        [
          'organization' => 'Impossible Foods',
          'description' => 'Honors exceptional dedication to helping customers.',
        ],
      'Salesperson of the Quarter/Year Award' =>
        [
          'organization' => 'Palantir Technologies',
          'description' => 'Acknowledges top sales achievements.',
        ],
      'Rookie of the Year Award' =>
        [
          'organization' => 'Toast, Inc',
          'description' => 'Given to outstanding new employees.',
        ],
      'Safety Award' =>
        [
          'organization' => 'Atlassian',
          'description' => 'Recognizes adherence to safety protocols and a commitment to a safe work environment.',
        ],
      'Innovation Award' =>
        [
          'organization' => 'ServiceNow',
          'description' => 'Celebrates creative ideas or solutions that benefit the organization.',
        ],
      'Volunteer of the Year Award' =>
        [
          'organization' => 'DocuSign',
          'description' => 'Honors significant contributions to a community or non-profit organization.',
        ],
      'Length of Service Award' =>
        [
          'organization' => 'Nio Inc.',
          'description' => 'Recognizes loyalty and dedication for years of employment.',
        ],
      'Leadership Award' =>
        [
          'organization' => 'AstraZeneca',
          'description' => 'Given to individuals who demonstrate strong leadership qualities.',
        ],
      'Above and Beyond Award' =>
        [
          'organization' => 'Spotify Technology S.A.',
          'description' => 'For going the extra mile in job duties or projects.',
        ],
      'Peer Recognition Award' =>
        [
          'organization' => 'Square Enix Holdings Co., Ltd.',
          'description' => 'Nominated and chosen by colleagues for positive contributions.',
        ],
      'Wellness Champion Award' =>
        [
          'organization' => 'ASML Holding N.V.',
          'description' => 'Recognizes efforts in promoting health and well-being in the workplace.',
        ],
      'Best Idea/Suggestion Award' =>
        [
          'organization' => 'Shopify Inc.',
          'description' => 'Honors valuable input that improves processes or efficiency.',
        ],
      'Educator of the Year Award' =>
        [
          'organization' => 'GitLab Inc.',
          'description' => 'For outstanding teachers or trainers.',
        ],
      'Community Impact Award' =>
        [
          'organization' => 'Wise',
          'description' => 'Recognizes significant positive contributions to the local community.',
        ],
      'Mentorship Award' =>
        [
          'organization' => 'Klarna Bank AB',
          'description' => 'Honors individuals who excel at guiding and developing others.',
        ],
      'Attendance Award' =>
        [
          'organization' => 'Roku Inc.',
          'description' => 'For consistent and reliable presence at work.',
        ],
      'Professional Development Award' =>
        [
          'organization' => 'Veeam Software',
          'description' => 'Recognizes commitment to continuous learning and skill improvement.',
        ],
      'Star Performer Award' =>
        [
          'organization' => 'UiPath Inc.',
          'description' => 'A general award for consistently high performance and positive attitude.',
        ],
    ];
    $awards = shuffle_assoc($awards);

    $date = 2010;
    $awardIndex = 0;
    foreach ($awards as $award => $awardInfo) {
      $date += 2;
      if ($awardIndex < 3) {
        $this->awards()->create(
          [
            'name' => $award,
            'organization' => $awardInfo['organization'],
            'description' => $awardInfo['description'],
            'date' => Carbon::parse("01-01-{$date}"),
            'active' => true,
          ]
        );
      }
      $awardIndex++;
    }
  }
  private function addLanguages()
  {

    $languages = [
      'English',
      'Spanish',
      'French',
      'German',
      'Russian',
    ];
    $languages = shuffle_assoc($languages);
    $languageIndex = 0;
    foreach ($languages as $language) {
      if ($languageIndex < 2) {
        $this->languages()->create([
          'name' => $language,
          'level' => rand(15, 20) * 5,
          'active' => true,
        ]);
      }
      $languageIndex++;
    }
  }

  private function addSkills()
  {
    $skills = [
      'Project Management: The ability to plan, execute, and close projects successfully.',
      'Data Analysis: Interpreting data to identify trends and draw conclusions.',
      'Software Development (e.g., Python, Java): Writing and maintaining code for applications.',
      'Digital Marketing: Promoting products or services using online channels like social media, SEO, and content marketing.',
      'Financial Modeling: Creating abstract representations of real-world financial situations to predict outcomes.',
      'Graphic Design: Creating visual content using software and principles of design.',
      'Cybersecurity: Protecting systems and networks from digital attacks.',
      'Foreign Language Proficiency (e.g., Spanish, French, Mandarin): Communicating effectively in another language.',
      'Cloud Computing (e.g., AWS, Azure): Managing and deploying applications on cloud platforms.',
      'Technical Writing: Creating clear and concise documentation for complex technical topics.',
    ];
    $skills = shuffle_assoc($skills);
    $skillIndex = 0;
    foreach ($skills as $skill) {
      if ($skillIndex < 3) {
        Skill::create([
          'name' => $skill,
          'level' => rand(15, 20) * 5,
          'active' => true,
          'user_id' => $this->id
        ]);
      }
      $skillIndex++;
    }
  }
  private function addProfile()
  {
    $abouts = [
      'Highly motivated and results-driven professional with a proven track record of adapting to dynamic environments and acquiring new skills rapidly. My career journey has equipped me with a diverse skill set, spanning from project coordination to data analysis, always seeking opportunities to learn and contribute meaningfully',
      'A dedicated and enthusiastic team player with a strong commitment to fostering positive and productive work environments. I excel at interpersonal communication and building strong relationships with colleagues and clients alike, believing that effective collaboration is key to achieving exceptional results.',
      'Analytical and detail-oriented professional with a strong aptitude for problem-solving and optimizing operational efficiency. I am driven by a desire to identify root causes of issues and implement sustainable, effective solutions.',
      'Customer-centric professional passionate about delivering exceptional service and building lasting client relationships. My approach is always to understand client needs deeply and provide tailored solutions that exceed expectations. I excel at active listening and empathetic communication, ensuring clients feel heard and valued. ',
      'Creative and forward-thinking individual with a flair for generating innovative ideas and transforming them into tangible outcomes. I am passionate about exploring new approaches and pushing the boundaries of traditional solutions. My background demonstrates an ability to conceptualize unique designs, compelling content, or novel strategies. I possess strong visual communication skills and a knack for storytelling that resonates with target audiences. ',
      'Highly motivated and results-oriented business graduate with a strong foundation in data analysis, project coordination, and customer support. Eager to apply theoretical knowledge and practical experience gained from academic projects and an internship to contribute to a dynamic team.',
      "Seasoned Marketing Manager with eight years of progressive experience in digital advertising and brand development. Adept at driving strategic initiatives, leading cross-functional teams, and optimizing campaign performance. Recognized for consistently exceeding targets and fostering collaborative environments and committed to achieving organizational goals.",
      "Passionate and adaptable professional transitioning from event management to software development. Bringing a unique blend of strong communication, problem-solving, and client relationship management honed over seven years in event planning. Eager to leverage a fresh perspective and a solid understanding of Python and web development frameworks to excel in a new and challenging environment",
      "Results-driven Full-Stack Developer with five years of experience designing, developing, and deploying robust web applications. Proficient in Python, JavaScript, React, AWS, and Agile methodologies. Committed to building scalable solutions and leveraging technology to drive business impact.",
      "Dynamic and persuasive Account Executive with a proven track record of consistently exceeding sales quotas and building strong client relationships. Skilled in negotiation, active listening, and problem resolution and passionate about delivering exceptional client experiences. Adept at identifying customer needs and providing tailored solutions to drive growth."
    ];
    shuffle($abouts);
    $this->profile()->create([
      'fullname' => $this->name,
      'gender' => is_female($this->name) ? 'female' : 'male',
      'job_title' => $this->job_title,
      'transparent_profile_image' => null,
      'slogan' => 'Hi',
      'about' => $abouts[0],
      'email' => $this->email,
    ]);
  }

  private function addSubscription($plan)
  {
    // $this->subscription()->create([
    //   'plan_name' => $plan->name,
    //   'plan_id' => $plan->id,
    //   'ends_at' => Carbon::now()->addDays(30),
    //   'renew' => false,
    //   'status' => 'active',
    // ]);
  }

  private function addResume()
  {
    $defaultResumeSlug = 'smit';
    $template = Template::free()->where('folder', $defaultResumeSlug)->first();
    if ($template) {
      $resume = $this->resumes()->create([
        'name' => $this->job_title,
        'template_id' => $template->id,
      ]);

      return $resume;
    }
    return false;
  }

  public function addBlocks(Resume $resume)
  {
    if ($resume->template) {
      $sections = $resume->template->sections;
      foreach ($sections as $section) {
        $inc = 0;
        $widgets = collect(explode(',', $section->default_widgets))->filter(fn($widget) => trim($widget) != '');
        foreach ($widgets as $widgetSlug) {
          $widgetSlug = trim($widgetSlug);
          $widgetInfo = explode(':', $widgetSlug);
          $_widgetInfo = explode(':', $widgetSlug);
          $widgetName = trim($widgetInfo[0]);
          $widget = Widget::where('name', $widgetName)->first();
          $from = null;
          $to = null;
          $active = true;
          $height = 10;
          $bold = false;
          $italic = false;
          $underline = false;
          $strikethrough = false;
          $uppercase = false;
          $radius = 0;
          $filter = null;
          $color = null;
          $align = null;
          $title = null;
          $dateformat = 'year';
          if (isset($widgetInfo[1]) && trim($widgetInfo[1]) != '') {
            $widgetSettings = explode('|', $widgetInfo[1]);
            foreach ($widgetSettings as $key => $widgetSetting) {
              $widgetSettingArr = explode('=', $widgetSetting);
              if (count($widgetSettingArr) > 1) {
                $widgetSettingName = trim($widgetSettingArr[0]);
                $widgetSettingValue = trim($widgetSettingArr[1]);
                if ($widgetSettingValue != '') {
                  if ($widgetSettingName == 'from') {
                    $from = (int) $widgetSettingValue;
                  }
                  if ($widgetSettingName == 'to') {
                    $to = (int) $widgetSettingValue;
                  }
                  if ($widgetSettingName == 'height') {
                    $height = (int) $widgetSettingValue;
                  }
                  if ($widgetSettingName == 'bold') {
                    $bold = $widgetSettingValue == 'true' ? true : false;
                  }
                  if ($widgetSettingName == 'italic') {
                    $italic = $widgetSettingValue == 'true' ? true : false;
                  }
                  if ($widgetSettingName == 'underline') {
                    $underline = $widgetSettingValue == 'true' ? true : false;
                  }
                  if ($widgetSettingName == 'strikethrough') {
                    $strikethrough = $widgetSettingValue == 'true' ? true : false;
                  }
                  if ($widgetSettingName == 'uppercase') {
                    $uppercase = $widgetSettingValue == 'true' ? true : false;
                  }
                  if ($widgetSettingName == 'radius') {
                    $radius = (int) $widgetSettingValue;
                  }
                  if ($widgetSettingName == 'filter') {
                    $filter = $widgetSettingValue;
                  }
                  if ($widgetSettingName == 'color') {
                    $color = $widgetSettingValue;
                  }
                  if ($widgetSettingName == 'align') {
                    $align = $widgetSettingValue;
                  }
                  if ($widgetSettingName == 'dateformat') {
                    $dateformat = $widgetSettingValue;
                  }
                  if ($widgetSettingName == 'title') {
                    if ($widgetSettingValue == 'empty') {
                      $title = ' ';
                    } else {
                      $title = $widgetSettingValue;
                    }
                  }
                }
              }
            }
          }
          if ($widgetName == 'job_title' || $widgetName == 'fullname') {
            // $uppercase = true;
          }
          // if ($widgetName == 'experiences') {
          //   $from = 0;
          //   $to = 2;
          // }
          // if ($widgetName == 'educations') {
          //   $from = 0;
          //   $to = 3;
          // }
          if ($widget) {
            $block = Block::create([
              'widget_id' => $widget->id,
              'resume_id' => $resume->id,
              'resume_page' => 1,
              'title' => $title,
              'from' => $from,
              'to' => $to,
              'height' => $height,
              'bold' => $bold,
              'italic' => $italic,
              'underline' => $underline,
              'strikethrough' => $strikethrough,
              'radius' => $radius,
              'filter' => $filter,
              'color' => $color,
              'align' => $align,
              'uppercase' => $uppercase,
              'active' => $active,
              'dateformat' => $dateformat,
              'section' => $section->section,
              'order' => $inc,
            ]);
            $inc++;
          } else {
          }
        }
      }
    }
  }

}