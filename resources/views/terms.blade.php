@extends('layouts.app')

@section('title')
<title>Terms and Conditions</title>
@endsection

@section('content')

@push('scripts')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
@endpush

<style>
    html {
         scroll-behavior: smooth;
    }

    /* .justify {
        text-align: justify;
        text-justify: inter-word;
    }
    
    #myBtn {
      display: none;
      position: fixed;
      bottom: 20px;
      right: 30px;
      z-index: 99;
      font-size: 18px;
      border: none;
      outline: none;
      background-color: white;
      color: black;
      cursor: pointer;
      padding: 15px;
      border-radius: 4px;
      -webkit-transition-duration: 0.4s;
     transition-duration: 0.4s;
    }
    
    #myBtn:hover {
      background-color: gray;
      color: white;
    } */
</style>

<h2 class="welcome-title mt-3 display-5">Terms and Conditions</h2>
<div class="justify lead">
    <div class="row mt-3 mb-5">
        <div class="col">
            <div class="terms-content">
                <p>These terms and conditions outline the rules and regulations for the use of Alkansya's Website.</p>
                
                <p>By accessing this website we assume you accept these terms and conditions in full. Do not continue to use Alkansya's website 
                if you do not accept all of the terms and conditions stated on this page.</p>
                
                <p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice
                and any or all Agreements: "Collector" and "Member" refers to you, the person accessing this website
                and accepting the terms and conditions. “The Cooperative”, "The Developer" and “Us”, refers
                to our Cooperative. “Party”, “Parties”, or “Us”, refers to both the Client and The Developers, or either the Client
                or the developers. All terms refer to the offer, acceptance and consideration of payment necessary to undertake
                the process of our assistance to the Client in the most appropriate manner, whether by formal meetings
                of a fixed duration, or any other means, for the express purpose of meeting the Client’s needs in respect
                in accordance with and subject to, prevailing law
                of Philippines. Any use of the above terminology or other words in the singular, plural,
                capitalisation and/or he/she or they, are taken as interchangeable and therefore as referring to same.</p>
            </div>
        </div>
    </div>
    <div class="row my-5" id="moreInfo">
        <div class="col">
            <div class="terms-title h4 pb-2">Our Services</div>
            <div class="terms-content">
                <p>Alkansya serves as an online savings bank for the people in the organization in a particular area with an aim to provide financial assistance to those who are in need of money</p>
                <p>Rules and Regulations:</p>
                <ol>
                    <li>Application for member starts on the early months every year. The registration of members depends on the <b>Administrator's</b> decision.</li>
                    <li>The minimum loan amount depends on the member's savings.</li>
                    <li>Loan Request <b>MUST</b> not be greater than the <b>Member's savings</b>.</li>
                    <li>The minimum deposit per year is ₱1825, having a saving that is greater than the said amount increases that amount once the member withdraws it by the end of the year.</li>
                    <li>Deactivating your account let's the member withdraw his/her current savings regardless of the member's number of shares and its patronage refund.</li>
                    {{-- <mark class="text-info"><u>https://alkansya.com</u></mark>
                    <mark class="text-info"><u>https://alkansya.com</u></mark> --}}
                </ol>
                <p>
                    <h5>Note:</h5>
                    <li>Requesting a loan that is greater than the member's savings is up to the <b>Administrator's discretion</b> in accepting the said request.</li>
                </p>
            </div>
        </div>
    </div>
    <div class="row my-5">
        <div class="col">
            <div class="terms-title h4 pb-2">License</div>
            <div class="terms-content">
                <p>Unless otherwise stated, Alkansya and/or it’s licensors own the intellectual property rights for
                all material on Alkansya. All intellectual property rights are reserved. You may view and/or print
                pages from https://alkansya.com/termsAndConditions <!--Change the subdomain--> for your own personal use subject to restrictions set in these terms and conditions.</p>
                <p>You must not:</p>
                <ol>
                    <li>Republish material from https://alkansya.com</li>
                    <li>Sell, rent or sub-license material from https://alkansya.com</li>
                    <li>Reproduce, duplicate or copy material from https://alkansya.com</li>
                </ol>
                <p>Redistribute content from Alkansya (unless content is specifically made for redistribution).</p>
            </div>
        </div>
    </div>
    <div class="row my-5">
        <div class="col">
            <div class="terms-title h4 pb-2">Reservation of Rights</div>
            <div class="terms-content">
                <p>We reserve the right at any time and in its sole discretion to request that you remove all links or any particular
                    link to our Web site. You agree to immediately remove all links to our Web site upon such request. We also
                    reserve the right to amend these terms and conditions and its linking policy at any time. By continuing
                    to link to our Web site, you agree to be bound to and abide by these linking terms and conditions.</p>
            </div>
        </div>
    </div>
    <div class="row my-5">
        <div class="col">
            <div class="terms-title h4 pb-2">Content Liability</div>
            <div class="terms-content">
                <p>We shall have no responsibility or liability for any content appearing on your Web site. You agree to indemnify
                    and defend us against all claims arising out of or based upon your Website. No link(s) may appear on any
                    page on your Web site or within any context containing content or materials that may be interpreted as
                    libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or
                    other violation of, any third party rights.</p>
            </div>
        </div>
    </div>
    <div class="row my-5">
        <div class="col">
            <div class="terms-title h4 pb-2">Disclaimer</div>
            <div class="terms-content">
                <p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website (including, without limitation, any warranties implied by law in respect of satisfactory quality, fitness for purpose and/or the use of reasonable care and skill). Nothing in this disclaimer will:</p>
                <ol>
                    <li>limit or exclude our or your liability for death or personal injury resulting from negligence;</li>
                    <li>limit or exclude our or your liability for fraud or fraudulent misrepresentation;</li>
                    <li>limit any of our or your liabilities in any way that is not permitted under applicable law; or</li>
                    <li>exclude any of our or your liabilities that may not be excluded under applicable law.</li>
                </ol>
                <p>The limitations and exclusions of liability set out in this Section and elsewhere in this disclaimer: (a)
                are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer or
                in relation to the subject matter of this disclaimer, including liabilities arising in contract, in tort
                (including negligence) and for breach of statutory duty.</p>
                <p>To the extent that the website and the information and services on the website are provided free of charge,
                we will not be liable for any loss or damage of any nature.</p>
            </div>
        </div>
    </div>
    <div class="row my-5">
        <div class="col">
            <div class="terms-title h4 pb-2">Privacy Policy</div>
            <div class="terms-content">
                <h5>Your privacy is critically important to us.</h5>

                <p>It is Alkansya’s policy to respect your privacy regarding any information we may collect while operating our website. 
                    This Privacy Policy applies to <a href="https://alkansya.com">https://alkansya.com</a>.
                    We respect your privacy and are committed to protecting personally identifiable information you may provide us through the Website. 
                    We have adopted this privacy policy ("Privacy Policy") to explain what information may be collected on our Website, how we use this information, 
                    and under what circumstances we may disclose the information to third parties. This Privacy Policy applies only to information we collect through 
                    the Website and does not apply to our collection of information from other sources.</p>

                <p>This Privacy Policy, together with the Terms and conditions, set forth the general rules and policies governing your use of our Website. Depending on your activities when visiting our Website, you may be required to agree to additional terms and conditions.</p>
            </div>
        </div>
    </div>
    <div class="row my-5">
        <div class="col">
            <div class="terms-title h4 pb-2">Gathering of Personally-Identifying Information</div>
            <div class="terms-content">
                <p>Certain visitors to Alkansya’s websites choose to interact with Alkansya in ways that require Alkansya to gather personally-identifying information. 
                    The amount and type of information that Alkansya gathers depends on the nature of the interaction.</p>
            </div>
        </div>
    </div>
    <div class="row my-5">
        <div class="col">
            <div class="terms-title h4 pb-2">Security</div>
            <div class="terms-content">
                <p>The security of your Personal Information is important to us, but remember that no method of transmission over the Internet, 
                    or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your Personal Information, 
                    we cannot guarantee its absolute security.</p>
            </div>
        </div>
    </div>
    <div class="row my-5">
        <div class="col">
            <div class="terms-title h4 pb-2">Protection of Certain Personally-Identifying Information</div>
            <div class="terms-content">
                <p>Alkansya discloses potentially personally-identifying and personally-identifying information only to those of its employees, contractors and affiliated organizations that:
                    <br>
        
                    <ol type="i">
                        <li>Need to know that information in order to process it on Alkansya’s behalf or to provide services available at Alkansya’s website, and</li>
                        <li>That have agreed not to disclose it to others. By using Alkansya’s website, 
                            you consent to the transfer of such information to them. Alkansya will not rent or sell potentially personally-identifying and personally-identifying information to anyone. 
                            Alkansya discloses potentially personally-identifying and personally-identifying information only in response to a subpoena, court order or other governmental request, or when Alkansya believes in good faith that disclosure is reasonably necessary to protect the property or rights of Alkansya, 
                            third parties or the public at large.</li>
                    </ol>
        
                    <p>If you are a registered user of https://alkansya.com and have supplied your email address, Alkansya may occasionally send you an email to tell you about new features, 
                    or just keep you up to date with what’s going on with Alkansya.If you send us a request (for example via email or via phone number), we reserve the right to publish 
                    it in order to help us clarify or respond to your request or to help us support other users. Alkansya takes all measures reasonably necessary to protect against the unauthorized access, use, 
                    alteration or destruction of potentially personally-identifying and personally-identifying information.</p>
            </div>
        </div>
    </div>
    <div class="row my-5">
        <div class="col">
            <div class="terms-title h4 pb-2">Privacy Policy Changes</div>
            <div class="terms-content">
                <p>Although most changes are likely to be minor, Alkansya may change its Privacy Policy from time to time, and in Alkansya’s sole discretion. Alkansya encourages users to frequently check this page for any changes to its Privacy Policy. Your continued use of this site after any change in this Privacy Policy will constitute your acceptance of such change.</p>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col">
            <div class="terms-title h4 pb-2">Contact Us</div>
            <div class="terms-content">
                <p>If you have any questions, complaints, or comments regarding these Terms and Conditions, please contact us via email: alkansya@gmail.com or via contact number: 0936 526 4573 </p>
            </div>
        </div>
    </div>
</div>
@endsection