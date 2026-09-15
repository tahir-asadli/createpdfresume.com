<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Resume;
use App\Models\Section;
use App\Models\Template;
use App\Models\User;
use App\Models\Widget;
use Illuminate\Database\Seeder;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Tahir Əsədli',
            'email' => 'tahir-asadov@outlook.com',
            'email_verified_at' => now(),
            'password' => '4+}"z^]&n%8QWWT'
        ]);

        $source_path = storage_path('app/public/template-images');
        $pattern = $source_path . '/*.{png,jpg}';
        $images = glob($pattern, GLOB_BRACE);
        array_walk($images, function ($image) {
            unlink($image);
        });

        $source_path = resource_path('templates');
        $folders = array_filter(scandir($source_path), function ($item) use ($source_path) {
            return $item !== '.' && $item !== '..' && is_dir($source_path . DIRECTORY_SEPARATOR . $item);
        });

        $manager = new ImageManager(new Driver());
        $destination_path = storage_path('app/public/template-images');
        foreach ($folders as $folder) {
            $path = $source_path . DIRECTORY_SEPARATOR . $folder;
            $pattern = $path . '/*.{jpg,jpeg,png}';
            $images = glob($pattern, GLOB_BRACE);
            if (count($images)) {
                $image = $manager->read($images[0]);
                $filedestination = $destination_path . '/' . $folder . '.png';
                $image->toPng()->save($filedestination);
            }
        }

        // $standart = Plan::create([
        //     'name' => 'Standart',
        //     'slug' => 'standart',
        //     'features' => "",
        //     'day_count' => 30,
        //     'resume_count' => 3,
        //     'generation_count' => 5,
        //     'page_count' => 2,
        //     'price' => '500',
        //     'active' => true
        // ]);
        $premium = Plan::create([
            'name' => 'Premium',
            'slug' => 'premium',
            'features' => "",
            'day_count' => 30,
            'resume_count' => 10,
            'generation_count' => 10,
            'page_count' => 3,
            'price' => '850',
            'usd' => '500',
            'active' => true
        ]);

        $widgets = [
            'Skills' => __('Skills'),
            'Languages' => __('Languages'),
            'Experiences' => __('Experiences'),
            'Educations' => __('Educations'),
            'Socials' => __('Socials'),
            'Projects' => __('Projects'),
            'Awards' => __('Awards'),
            'Certificates' => __('Certificates'),
            'Hobbies' => __('Hobbies'),
            'References' => __('References'),
        ];
        foreach ($widgets as $key => $widget) {
            Widget::create([
                'title' => $widget,
                'name' => strtolower($key),
                'multiple' => true,
                'active' => true,
            ]);
        }
        $widgets = [
            'Fullname' => __('Fullname'),
            'Job title' => __('Job title'),
            'Slogan' => __('Slogan'),
            'About' => __('About'),
            'Contacts' => __('Contacts'),
            'Phone' => __('Phone'),
            'Email' => __('E-mail'),
            'Web' => __('Web'),
            'Address' => __('Address'),
            'Image' => __('Profile image'),
            'h1' => __('Heading 1'),
            'h2' => __('Heading 2'),
            'h3' => __('Heading 3 - Section name'),
            'h4' => __('Heading 4'),
            'h5' => __('Heading 5'),
            'h6' => __('Heading 6'),
            'p' => __('Paragraph'),
            'space' => __('Empty space'),
            'ol' => __('Ordered list'),
            'ul' => __('Unordered list'),
        ];

        foreach ($widgets as $key => $widget) {
            Widget::create([
                'title' => $widget,
                'name' => str_replace(' ', '_', strtolower($key)),
                'multiple' => false,
                'active' => true,
            ]);
        }

        // Free

        $christine = Template::create([
            'name' => 'Crowder',
            'folder' => 'christine-crowder',
            'image' => 'christine-crowder.png',
            'layout' => 'layout_crovder',
            'colors' => '#108ac6,#43454b,#414042,#707176,#85878b,#c4c5c7',
            'plan_id' => null,
            'active' => true,
        ]);
        $maron = Template::create([
            'name' => 'Maron',
            'folder' => 'maron-roxenas',
            'image' => 'maron-roxenas.png',
            'layout' => 'layout_maron',
            'colors' => '#c52c27,#2c2829,#dddadc,#c6c5c3',
            'plan_id' => null,
            'active' => true,
        ]);

        // Standart

        $greta = Template::create([
            'name' => 'Greta',
            'folder' => 'greta',
            'plan_id' => $premium->id,
            'image' => 'greta.png',
            'layout' => 'layout_greta',
            'colors' => '#f3a75c,#292829,#414042,#58595b,#808285,#cccccc',
            'active' => true,
        ]);
        $graphic = Template::create([
            'name' => 'Graphic',
            'folder' => 'graphic',
            'image' => 'graphic.png',
            'layout' => 'layout_graphic',
            'colors' => '#f37159,#2c2e35,#e8e1cf,#36404a',
            'active' => true,
        ]);

        $lena = Template::create([
            'name' => 'Lena',
            'folder' => 'lena',
            'image' => 'lena.png',
            'layout' => 'layout_lena',
            'colors' => '#292a2d,#f9efe2,#3b3f4a,#a8795b,#466265',
            'active' => true,
        ]);
        $paper = Template::create([
            'name' => 'Paper',
            'folder' => 'paper',
            'layout' => 'layout_paper',
            'image' => 'paper.png',
            'colors' => '#292a2d,#444444,#d7d7d7,#34938d,#66f0e8,#dd4220,#ffa693',
            'active' => true,
        ]);

        // Premium

        $miller = Template::create([
            'name' => 'Miller',
            'folder' => 'miller',
            'image' => 'miller.png',
            'layout' => 'layout_miller',
            'colors' => '#44368b,#ea61ea,#ffe8dc',
            'active' => true,
        ]);
        $lukas = Template::create([
            'name' => 'Lucas',
            'folder' => 'lukas',
            'image' => 'lukas.png',
            'layout' => 'layout_lukas',
            'colors' => '#000000,#222222,#444444,#666666,#dddddd,#1470e6',
            'active' => true,
        ]);
        $smit = Template::create([
            'name' => 'Smith',
            'folder' => 'smit',
            'image' => 'smit.png',
            'layout' => 'layout_smit',
            'colors' => '#000000,#2c2c2c,#444444,#c6c6c6,#4e4ea6,#4e85a6,#72a64e,#bd4d4d',
            'active' => true,
        ]);
        $keyt = Template::create([
            'name' => 'Kate',
            'folder' => 'keyt',
            'image' => 'keyt.png',
            'layout' => 'layout_keyt',
            'colors' => '#000000,#222222,#444444,#797979,#e3e3e3,#f2f2f2,#ca6d18,#4e4ea6,#4e85a6,#72a64e,#bd4d4d,#fff2d2',
            'active' => true,
        ]);

        $aron = Template::create([
            'name' => 'Aron',
            'folder' => 'aron',
            'image' => 'aron.png',
            'layout' => 'layout_aron',
            'colors' => '#8055a2,#1e1e1e,#545454,#444444,#737373,#a6a6a6,#d9d9d9,#0097b2,#00bf63,#ff3131',
            'active' => true,
        ]);

        $karolin = Template::create([
            'name' => 'Caroline',
            'folder' => 'karolin',
            'image' => 'karolin.png',
            'layout' => 'layout_karolin',
            'colors' => '#242424,#444444,#c6c6c6,#ededed,#f7f8f8,#2d3e5b,#db9291',
            'active' => true,
        ]);

        $bill = Template::create([
            'name' => 'Bill Gates',
            'folder' => 'bill-gates',
            'image' => 'bill-gates.jpg',
            'layout' => 'layout_bill_gates',
            'colors' => '#242424,#454541,#eaeae8,#444b8b,#2b2f69,#dfd685',
            'active' => true,
        ]);

        $sarah = Template::create([
            'name' => 'Sarah',
            'folder' => 'sarah',
            'image' => 'sarah.jpg',
            'layout' => 'layout_sarah',
            'colors' => '#000000,#2c2c2c,#444444,#c6c6c6,#e3e3e3,#f2f2f2,#f4f1ec,#4e4ea6,#4e85a6,#72a64e,#bd4d4d,#fff2d2',
            'active' => true,
        ]);

        $press = Template::create([
            'name' => 'Press',
            'folder' => 'press',
            'image' => 'press.jpg',
            'layout' => 'layout_press',
            'styles' => 'dark,maroon,pink,red,blue,green,coffee,cream',
            'colors' => '#090607,#444444,#f3f0f1,#c31952,#f5deb3,#e6467b,#ec3253,#dd4220,#002875,#040000,#2350ab,#4e1f00,#ae7f61,#466233,#a6c369,#11759d',
            'active' => true,
        ]);

        $code = Template::create([
            'name' => 'Code',
            'folder' => 'code',
            'image' => 'code.jpg',
            'layout' => 'layout_code',
            'styles' => 'dark,pink,red,blue,green,coffee,cream',
            'colors' => '#000000,#646464,#5b0ecc,#f4f4f4',
            'active' => true,
        ]);

        // KAROLIN BEGIN

        Section::create([
            'name' => 'Header Left',
            'section' => 'header-left',
            'default_widgets' => 'image:radius=9999',
            'template_id' => $karolin->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Header Right',
            'section' => 'header-right',
            'default_widgets' => 'space:height=15,fullname,space:height=5,job_title',
            'template_id' => $karolin->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'space:height=10,contacts:title=empty,about,experiences:from=1|to=3',
            'template_id' => $karolin->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Footer Left',
            'section' => 'footer-left',
            'default_widgets' => 'educations:from=1|to=2',
            'template_id' => $karolin->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Footer Right',
            'section' => 'footer-right',
            'default_widgets' => 'socials,certificates:from=1|to=1',
            'template_id' => $karolin->id,
            'active' => true,
        ]);
        // KAROLIN BEGIN

        // content
// footer-left
// footer-right

        // GRETA BEGIN

        Section::create([
            'name' => 'Sidebar',
            'section' => 'sidebar',
            'default_widgets' => 'space:height=40,image:radius=9999|align=center|filter=effect-5,contacts,educations,languages',
            'template_id' => $greta->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'space:height=40,fullname:uppercase=true,job_title:uppercase=true,about:title=ABOUT,experiences,hobbies,skills',
            'template_id' => $greta->id,
            'active' => true,
        ]);

        // GRETA END



        // MARON STAR

        Section::create([
            'name' => 'Header left',
            'section' => 'header-left',
            'default_widgets' => 'space:height=10,image:radius=9999|align=center|filter=effect-1',
            'template_id' => $maron->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Header right',
            'section' => 'header-right',
            'default_widgets' => 'space:height=10,socials,space,about:title=About',
            'template_id' => $maron->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Header',
            'section' => 'header',
            'default_widgets' => 'fullname:uppercase=true,job_title:uppercase=true,space:height=15,educations:from=1|to=2',
            'template_id' => $maron->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Sidebar',
            'section' => 'sidebar',
            'default_widgets' => 'skills,languages',
            'template_id' => $maron->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'experiences:from=1|to=2,hobbies',
            'template_id' => $maron->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Footer',
            'section' => 'footer',
            'template_id' => $maron->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Footer Left',
            'section' => 'footer-left',
            'template_id' => $maron->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Footer Right',
            'section' => 'footer-right',
            'template_id' => $maron->id,
            'active' => true,
        ]);

        //MARON END

        //CHRISTINE START

        Section::create([
            'name' => 'Header',
            'section' => 'header',
            'default_widgets' => 'job_title:uppercase=true,fullname:uppercase=true',
            'template_id' => $christine->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Sidebar',
            'section' => 'sidebar',
            'default_widgets' => 'image:radius=9999|align=center|filter=effect-1,contacts,educations,socials',
            'template_id' => $christine->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'about:title=ABOUT,experiences,languages',
            'template_id' => $christine->id,
            'active' => true,
        ]);

        //CHRISTINE END

        //GRAPHIC START

        Section::create([
            'name' => 'Header',
            'section' => 'header',
            'default_widgets' => '',
            'template_id' => $graphic->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Sidebar',
            'section' => 'sidebar',
            'default_widgets' => 'image:radius=9999|align=center|filter=effect-4,about:title=About,languages,contacts,skills',
            'template_id' => $graphic->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'job_title:uppercase=true,fullname:uppercase=true,space:height=20,experiences,educations',
            'template_id' => $graphic->id,
            'active' => true,
        ]);

        //GRAPHIC END


        //LENA START

        // Section::create([
        //     'name' => 'Header',
        //     'section' => 'header',
        //     'default_widgets' => 'fullname,job_title',
        //     'template_id' => $lena->id,
        //     'active' => true,
        // ]);

        Section::create([
            'name' => 'Sidebar',
            'section' => 'sidebar',
            'default_widgets' => 'image:radius=9999|align=center,about:title=About,skills,languages,contacts,hobbies',
            'template_id' => $lena->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'fullname:uppercase=true,job_title:uppercase=true,experiences,educations,socials',
            'template_id' => $lena->id,
            'active' => true,
        ]);

        //LENA END


        //PAPER START

        Section::create([
            'name' => 'Header',
            'section' => 'header',
            'default_widgets' => 'image:radius=9999|align=center,space:height=10,fullname:align=center,space:height=10,job_title:uppercase=true|align=center',
            'template_id' => $paper->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'about:title=About,experiences:from=1|to=3,educations:from=1|to=2,socials,contacts',
            'template_id' => $paper->id,
            'active' => true,
        ]);

        //PAPER END


        //MILLER START

        Section::create([
            'name' => 'Header Left',
            'section' => 'header-left',
            'default_widgets' => 'fullname,job_title',
            'template_id' => $miller->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Header Right',
            'section' => 'header-right',
            'default_widgets' => 'image:radius=5|align=right',
            'template_id' => $miller->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Header',
            'section' => 'header',
            'default_widgets' => 'about:title=About,experiences:from=1|to=3,educations:from=1|to=3',
            'template_id' => $miller->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Footer Left',
            'section' => 'footer-left',
            'default_widgets' => 'socials:from=1|to=3',
            'template_id' => $miller->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Footer Right',
            'section' => 'footer-right',
            'default_widgets' => 'contacts',
            'template_id' => $miller->id,
            'active' => true,
        ]);

        //MILLER END

        //LUKAS START

        Section::create([
            'name' => 'Header',
            'section' => 'header',
            'default_widgets' => 'fullname:uppercase=true,job_title:uppercase=true',
            'template_id' => $lukas->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Sidebar',
            'section' => 'sidebar',
            'default_widgets' => 'image:radius=9999|filter=effect-6|align=center,about:title=About,socials,languages',
            'template_id' => $lukas->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'experiences:from=1|to=3,educations:from=1|to=2,contacts',
            'template_id' => $lukas->id,
            'active' => true,
        ]);

        //LUKAS END

        //SMIT START

        Section::create([
            'name' => 'Header Left',
            'section' => 'header-left',
            'default_widgets' => 'image:radius=9999|filter=effect-1',
            'template_id' => $smit->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Header Right',
            'section' => 'header-right',
            'default_widgets' => 'fullname:uppercase=true,job_title',
            'template_id' => $smit->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Sidebar',
            'section' => 'sidebar',
            'default_widgets' => 'languages,skills,hobbies,contacts,socials',
            'template_id' => $smit->id,
            'active' => true,
        ]);

        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'about:title=ABOUT,experiences,educations:from=1|to=2',
            'template_id' => $smit->id,
            'active' => true,
        ]);

        //SMIT END



        //KEYT START
        Section::create([
            'name' => 'Header Left',
            'section' => 'header-left',
            'default_widgets' => 'space:height=15,image:radius=9999',
            'template_id' => $keyt->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Header Middle',
            'section' => 'header-middle',
            'default_widgets' => 'job_title,fullname,about',
            'template_id' => $keyt->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Header Right',
            'section' => 'header-right',
            'default_widgets' => '',
            'template_id' => $keyt->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Sidebar',
            'section' => 'sidebar',
            'default_widgets' => 'educations,contacts',
            'template_id' => $keyt->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'experiences:from=1|to=3,languages',
            'template_id' => $keyt->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Footer Left',
            'section' => 'footer-left',
            'default_widgets' => 'references',
            'template_id' => $keyt->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Footer Middle',
            'section' => 'footer-middle',
            'default_widgets' => '',
            'template_id' => $keyt->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Footer Right',
            'section' => 'footer-right',
            'default_widgets' => '',
            'template_id' => $keyt->id,
            'active' => true,
        ]);
        //KEYT END

        //ARON START
        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'fullname:uppercase=true|align=center,space:height=5,job_title:uppercase=true|align=center,space:height=10,about,experiences:from=1|to=3,educations:from=1|to=2,languages,contacts',
            'template_id' => $aron->id,
            'active' => true,
        ]);
        //ARON END

        // BILL BEGIN

        Section::create([
            'name' => 'Header Left',
            'section' => 'header-left',
            'default_widgets' => 'fullname,job_title,socials',
            'template_id' => $bill->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Header Right',
            'section' => 'header-right',
            'default_widgets' => 'contacts,languages',
            'template_id' => $bill->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'about:title=ABOUT ME,experiences:from=1|to=2|dateformat=daymonthname,educations:from=1|to=1|dateformat=daymonthname,hobbies',
            'template_id' => $bill->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Footer Left',
            'section' => 'footer-left',
            'default_widgets' => '',
            'template_id' => $bill->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Footer Right',
            'section' => 'footer-right',
            'default_widgets' => '',
            'template_id' => $bill->id,
            'active' => true,
        ]);
        // BILL END

        // SARAH BEGIN
        Section::create([
            'name' => 'Header Left',
            'section' => 'header-left',
            'default_widgets' => 'image:radius=9999',
            'template_id' => $sarah->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Header Right',
            'section' => 'header-right',
            'default_widgets' => 'fullname,job_title,space:height=10,contacts:title=empty',
            'template_id' => $sarah->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'about:title=empty',
            'template_id' => $sarah->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Footer Left',
            'section' => 'footer-left',
            'default_widgets' => 'skills,languages,socials',
            'template_id' => $sarah->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Footer Right',
            'section' => 'footer-right',
            'default_widgets' => 'experiences:from=1|to=3,educations:from=1|to=1,hobbies',
            'template_id' => $sarah->id,
            'active' => true,
        ]);
        // SARAH END

        // PRESS BEGIN
        Section::create([
            'name' => 'Header',
            'section' => 'header',
            'default_widgets' => 'fullname:align=center,job_title:align=center,contacts:title=empty',
            'template_id' => $press->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Sidebar',
            'section' => 'sidebar',
            'default_widgets' => 'about:title=empty,skills,languages,socials',
            'template_id' => $press->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'experiences:from=1|to=3,educations:from=1|to=2',
            'template_id' => $press->id,
            'active' => true,
        ]);
        // PRESS END

        // CODE BEGIN
        Section::create([
            'name' => 'Sidebar',
            'section' => 'sidebar',
            'default_widgets' => 'fullname:uppercase=true,job_title:uppercase=true,contacts:title=empty,space:height=60,about:title=ABOUT ME,skills,certificates:from=1|to=1|dateformat=daymonthname,languages,hobbies',
            'template_id' => $code->id,
            'active' => true,
        ]);
        Section::create([
            'name' => 'Content',
            'section' => 'content',
            'default_widgets' => 'experiences:from=1|to=3|dateformat=daymonthname,educations:from=1|to=2|dateformat=daymonthname,socials',
            'template_id' => $code->id,
            'active' => true,
        ]);
        // CODE END
    }

}
