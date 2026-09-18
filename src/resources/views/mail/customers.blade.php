@extends('mail.layout')
@section('title', __('Create a professional resume in minutes – with :sitename!', ['sitename' => config('app.name')]))
@section('preheader', __('Hello, :name. Are you ready to take the first step towards a successful career with a professional resume?', ['name' => $customer->firstName()]))
@section('unsubscribe')
<div class="unsubscribe">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td class="content-block powered-by">
                <a href="{{ route('unsubscribe',$customer->token) }}"
                    style="text-align: center;color: #666;font-size: 12px;">Unsubscribe</a>
            </td>
        </tr>
    </table>
</div>
@endsection

@section('content')
    <h2 style="font-weight: 500;margin: 0 0 10px 0;">{!! __('Hello, :name', ['name' => '<b>'.$customer->firstName().'</b>']) !!}</h2>
    <p>
        {{ __('Are you ready to take the first step towards a successful career with a professional resume?') }}
         <br>
         {!! __(':link allows you to create a perfect CV using an easy and user-friendly interface', ['link' => '<b><a class="violet" href="'.config('app.url').'">'.config('app.name').'</a></b>'])!!}</p>
    <p style="text-align: center;font-weight: bold;font-size: 19px;margin: 30px auto;">
        {{ __('Reasons to choose :sitename', ['sitename' => config('app.name')]) }}:</p>
    <ul>
        <li>{!! __('<b>Simple and intuitive drag-and-drop editor</b> – Easily edit your resume and customize its design to your liking.') !!}</li>
        <li>{!! __('<b>Apply unique filters to your profile photo</b> – Ensure your resume catches the eye immediately and makes a memorable impact.') !!}</li>
        <li>{!! __('<b>One-click PDF download</b> – Download your resume as a PDF anytime you need it.') !!}</li>
    </ul>
    <p style="text-align: center;font-weight: bold;font-size: 19px;margin: 30px auto;">{{ __('Our Subscription Plans:') }}</p>
    <ul>
        <li>
            {!! __('<b>Free plan:</b> :template templates, :resume resumes, :page pages, and :pdf resume generations per day. Unlimited downloads.', [
                'template' => $freeTemplateCount,
                'resume' => setting('freePlanCVLimit', config('site.freePlanCVLimit')),
                'page' => setting('freePlanPageLimit', config('site.freePlanPageLimit')),
                'pdf' => setting('freePlanPdfLimit', config('site.freePlanPdfLimit')),
            ]) !!}
        </li>
        @foreach ($plans as $plan)
            <li>
                {!! __('<b>:plan plan (:price/month):</b> :template templates, :resume resumes, :page pages, and :resume resume generations per day. Unlimited downloads plus online support.', [
                    'plan' => $plan->name,
                    'price' => priceUSD($plan->usd),
                    'template' => $plan->templates()->active()->count(),
                    'resume' => $plan->resume_count,
                    'page' => $plan->page_count,
                    'pdf' => $plan->generation_count,
                ]) !!}
                </li>
        @endforeach
    </ul>
    <p style="padding-bottom: 20px;color: #444;font-size: 16px;margin-top: 30px;">{{ __('Forget traditional and difficult design tools. With :sitename, easily create your resume and stand out in the job market!', ['sitename' => config('app.name')]) }}</p>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
        <tbody>
            <tr>
                <td align="center">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td align="center"> <a href="{{ config('app.url') }}"
                                        style="line-height: 1;padding: 15px 30px 12px 30px" target="_blank">{{ __('Start here') }}</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
