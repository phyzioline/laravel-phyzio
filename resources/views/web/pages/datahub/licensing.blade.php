@extends('web.layouts.app')

@section('title', __('Professional Equivalence & Licensing Guide'))

@section('content')
<div class="licensing-guide-wrapper bg-light py-5" style="margin-top: 130px;">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <h2 class="text-teal-700 font-weight-bold" style="color: #0d9488;">{{ __('Professional Equivalence & Licensing Guide') }}</h2>
            <p class="text-muted">{{ __('A step-by-step guide for physical therapists validating qualifications and obtaining licenses globally.') }}</p>
        </div>

        <!-- Selection Wizard -->
        <div class="card shadow border-0 mb-5">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="font-weight-bold text-teal-600" style="color: #0d9488;">{{ __('1. Select Source Country') }} <small class="text-muted">({{ __('Where you graduated') }})</small></label>
                            <select id="sourceCountry" class="form-control form-control-lg border-teal" style="border-color: #0d9488;">
                                <option value="" selected disabled>{{ __('Choose Country...') }}</option>
                                <option value="Afganistan">Afghanistan</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="American Samoa">American Samoa</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Angola">Angola</option>
                                <option value="Anguilla">Anguilla</option>
                                <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Aruba">Aruba</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Belize">Belize</option>
                                <option value="Benin">Benin</option>
                                <option value="Bermuda">Bermuda</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Bonaire">Bonaire</option>
                                <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Brazil">Brazil</option>
                                <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                <option value="Brunei">Brunei</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Burkina Faso">Burkina Faso</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Canary Islands">Canary Islands</option>
                                <option value="Cape Verde">Cape Verde</option>
                                <option value="Cayman Islands">Cayman Islands</option>
                                <option value="Central African Republic">Central African Republic</option>
                                <option value="Chad">Chad</option>
                                <option value="Channel Islands">Channel Islands</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Christmas Island">Christmas Island</option>
                                <option value="Cocos Island">Cocos Island</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Comoros">Comoros</option>
                                <option value="Congo">Congo</option>
                                <option value="Cook Islands">Cook Islands</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Cote DIvoire">Cote DIvoire</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Curaco">Curacao</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Czech Republic">Czech Republic</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominica">Dominica</option>
                                <option value="Dominican Republic">Dominican Republic</option>
                                <option value="East Timor">East Timor</option>
                                <option value="Ecuador">Ecuador</option>
                                <option value="Egypt">Egypt</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                <option value="Eritrea">Eritrea</option>
                                <option value="Estonia">Estonia</option>
                                <option value="Ethiopia">Ethiopia</option>
                                <option value="Falkland Islands">Falkland Islands</option>
                                <option value="Faroe Islands">Faroe Islands</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="French Guiana">French Guiana</option>
                                <option value="French Polynesia">French Polynesia</option>
                                <option value="French Southern Ter">French Southern Ter</option>
                                <option value="Gabon">Gabon</option>
                                <option value="Gambia">Gambia</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Germany">Germany</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Gibraltar">Gibraltar</option>
                                <option value="Great Britain">Great Britain</option>
                                <option value="Greece">Greece</option>
                                <option value="Greenland">Greenland</option>
                                <option value="Grenada">Grenada</option>
                                <option value="Guadeloupe">Guadeloupe</option>
                                <option value="Guam">Guam</option>
                                <option value="Guatemala">Guatemala</option>
                                <option value="Guinea">Guinea</option>
                                <option value="Guyana">Guyana</option>
                                <option value="Haiti">Haiti</option>
                                <option value="Hawaii">Hawaii</option>
                                <option value="Honduras">Honduras</option>
                                <option value="Hong Kong">Hong Kong</option>
                                <option value="Hungary">Hungary</option>
                                <option value="Iceland">Iceland</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="India">India</option>
                                <option value="Iran">Iran</option>
                                <option value="Iraq">Iraq</option>
                                <option value="Ireland">Ireland</option>
                                <option value="Isle of Man">Isle of Man</option>
                                <option value="Israel">Israel</option>
                                <option value="Italy">Italy</option>
                                <option value="Jamaica">Jamaica</option>
                                <option value="Japan">Japan</option>
                                <option value="Jordan">Jordan</option>
                                <option value="Kazakhstan">Kazakhstan</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Kiribati">Kiribati</option>
                                <option value="Korea North">Korea North</option>
                                <option value="Korea Sout">Korea South</option>
                                <option value="Kuwait">Kuwait</option>
                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                <option value="Laos">Laos</option>
                                <option value="Latvia">Latvia</option>
                                <option value="Lebanon">Lebanon</option>
                                <option value="Lesotho">Lesotho</option>
                                <option value="Liberia">Liberia</option>
                                <option value="Libya">Libya</option>
                                <option value="Liechtenstein">Liechtenstein</option>
                                <option value="Lithuania">Lithuania</option>
                                <option value="Luxembourg">Luxembourg</option>
                                <option value="Macau">Macau</option>
                                <option value="Macedonia">Macedonia</option>
                                <option value="Madagascar">Madagascar</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Malawi">Malawi</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Mali">Mali</option>
                                <option value="Malta">Malta</option>
                                <option value="Marshall Islands">Marshall Islands</option>
                                <option value="Martinique">Martinique</option>
                                <option value="Mauritania">Mauritania</option>
                                <option value="Mauritius">Mauritius</option>
                                <option value="Mayotte">Mayotte</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Midway Islands">Midway Islands</option>
                                <option value="Moldova">Moldova</option>
                                <option value="Monaco">Monaco</option>
                                <option value="Mongolia">Mongolia</option>
                                <option value="Montserrat">Montserrat</option>
                                <option value="Morocco">Morocco</option>
                                <option value="Mozambique">Mozambique</option>
                                <option value="Myanmar">Myanmar</option>
                                <option value="Nambia">Nambia</option>
                                <option value="Nauru">Nauru</option>
                                <option value="Nepal">Nepal</option>
                                <option value="Netherland Antilles">Netherland Antilles</option>
                                <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                <option value="Nevis">Nevis</option>
                                <option value="New Caledonia">New Caledonia</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Nicaragua">Nicaragua</option>
                                <option value="Niger">Niger</option>
                                <option value="Nigeria">Nigeria</option>
                                <option value="Niue">Niue</option>
                                <option value="Norfolk Island">Norfolk Island</option>
                                <option value="Norway">Norway</option>
                                <option value="Oman">Oman</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="Palau Island">Palau Island</option>
                                <option value="Palestine">Palestine</option>
                                <option value="Panama">Panama</option>
                                <option value="Papua New Guinea">Papua New Guinea</option>
                                <option value="Paraguay">Paraguay</option>
                                <option value="Peru">Peru</option>
                                <option value="Phillipines">Philippines</option>
                                <option value="Pitcairn Island">Pitcairn Island</option>
                                <option value="Poland">Poland</option>
                                <option value="Portugal">Portugal</option>
                                <option value="Puerto Rico">Puerto Rico</option>
                                <option value="Qatar">Qatar</option>
                                <option value="Republic of Montenegro">Republic of Montenegro</option>
                                <option value="Republic of Serbia">Republic of Serbia</option>
                                <option value="Reunion">Reunion</option>
                                <option value="Romania">Romania</option>
                                <option value="Russia">Russia</option>
                                <option value="Rwanda">Rwanda</option>
                                <option value="St Barthelemy">St Barthelemy</option>
                                <option value="St Eustatius">St Eustatius</option>
                                <option value="St Helena">St Helena</option>
                                <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                <option value="St Lucia">St Lucia</option>
                                <option value="St Maarten">St Maarten</option>
                                <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                <option value="Saipan">Saipan</option>
                                <option value="Samoa">Samoa</option>
                                <option value="Samoa American">Samoa American</option>
                                <option value="San Marino">San Marino</option>
                                <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="Senegal">Senegal</option>
                                <option value="Seychelles">Seychelles</option>
                                <option value="Sierra Leone">Sierra Leone</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Slovakia">Slovakia</option>
                                <option value="Slovenia">Slovenia</option>
                                <option value="Solomon Islands">Solomon Islands</option>
                                <option value="Somalia">Somalia</option>
                                <option value="South Africa">South Africa</option>
                                <option value="Spain">Spain</option>
                                <option value="Sri Lanka">Sri Lanka</option>
                                <option value="Sudan">Sudan</option>
                                <option value="Suriname">Suriname</option>
                                <option value="Swaziland">Swaziland</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Syria">Syria</option>
                                <option value="Tahiti">Tahiti</option>
                                <option value="Taiwan">Taiwan</option>
                                <option value="Tajikistan">Tajikistan</option>
                                <option value="Tanzania">Tanzania</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Togo">Togo</option>
                                <option value="Tokelau">Tokelau</option>
                                <option value="Tonga">Tonga</option>
                                <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                <option value="Tunisia">Tunisia</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Turkmenistan">Turkmenistan</option>
                                <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                <option value="Tuvalu">Tuvalu</option>
                                <option value="Uganda">Uganda</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Ukraine">Ukraine</option>
                                <option value="United Arab Erimates">United Arab Emirates</option>
                                <option value="United States of America">United States of America</option>
                                <option value="Uraguay">Uruguay</option>
                                <option value="Uzbekistan">Uzbekistan</option>
                                <option value="Vanuatu">Vanuatu</option>
                                <option value="Vatican City State">Vatican City State</option>
                                <option value="Venezuela">Venezuela</option>
                                <option value="Vietnam">Vietnam</option>
                                <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                <option value="Wake Island">Wake Island</option>
                                <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                <option value="Yemen">Yemen</option>
                                <option value="Zaire">Zaire</option>
                                <option value="Zambia">Zambia</option>
                                <option value="Zimbabwe">Zimbabwe</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
                        <div class="arrow-icon bg-teal text-white rounded-circle p-2 shadow-sm d-none d-md-block" style="background-color: #0d9488;">
                            <i class="las la-arrow-right font-weight-bold" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="arrow-icon bg-teal text-white rounded-circle p-2 shadow-sm d-md-none my-3" style="background-color: #0d9488;">
                            <i class="las la-arrow-down font-weight-bold" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="font-weight-bold text-teal-600" style="color: #0d9488;">{{ __('2. Select Target Country') }} <small class="text-muted">({{ __('Where you want to practice') }})</small></label>
                            <select id="targetCountry" class="form-control form-control-lg border-teal" style="border-color: #0d9488;">
                                <option value="" selected disabled>{{ __('Choose Country...') }}</option>
                                <option value="oman">{{ __('Oman') }}</option>
                                <option value="saudi_arabia">{{ __('Saudi Arabia') }}</option>
                                <option value="uae">{{ __('UAE') }}</option>
                                <option value="qatar">{{ __('Qatar') }}</option>
                                <option value="kuwait">{{ __('Kuwait') }}</option>
                                <option value="bahrain">{{ __('Bahrain') }}</option>
                                <option value="usa">{{ __('USA') }}</option>
                                <option value="uk">{{ __('UK') }}</option>
                                <option value="canada">{{ __('Canada') }}</option>
                                <option value="australia">{{ __('Australia') }}</option>
                                <option value="ireland">{{ __('Ireland') }}</option>
                                <option value="germany">{{ __('Germany') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Content Area -->
        <div id="requirementsSection" style="display: none;">
            <div class="row">
                <!-- Tabs Navigation -->
                <div class="col-md-3 mb-4">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active font-weight-bold py-3" id="v-pills-official-tab" data-toggle="pill" href="#v-pills-official" role="tab" aria-controls="v-pills-official" aria-selected="true" style="color: #0d9488;">
                            <i class="las la-file-alt mr-2"></i> {{ __('Official Requirements') }}
                        </a>
                        <a class="nav-link font-weight-bold py-3" id="v-pills-hours-tab" data-toggle="pill" href="#v-pills-hours" role="tab" aria-controls="v-pills-hours" aria-selected="false" style="color: #0d9488;">
                            <i class="las la-clock mr-2"></i> {{ __('Hours & Curriculum') }}
                        </a>
                        <a class="nav-link font-weight-bold py-3" id="v-pills-experience-tab" data-toggle="pill" href="#v-pills-experience" role="tab" aria-controls="v-pills-experience" aria-selected="false" style="color: #0d9488;">
                            <i class="las la-briefcase mr-2"></i> {{ __('Required Experience') }}
                        </a>
                        <a class="nav-link font-weight-bold py-3" id="v-pills-exams-tab" data-toggle="pill" href="#v-pills-exams" role="tab" aria-controls="v-pills-exams" aria-selected="false" style="color: #0d9488;">
                            <i class="las la-edit mr-2"></i> {{ __('Mandatory Exams') }}
                        </a>
                    </div>
                </div>

                <!-- Tabs Content -->
                <div class="col-md-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        
                        <!-- Official Requirements Tab -->
                        <div class="tab-pane fade show active" id="v-pills-official" role="tabpanel" aria-labelledby="v-pills-official-tab">
                            <div class="card shadow-sm border-0">
                                <div class="card-body p-4">
                                    <h4 class="text-teal-700 font-weight-bold mb-4" style="color: #0d9488;">{{ __('Official Documentation') }}</h4>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="las la-check-circle text-success mr-2"></i> {{ __('Bachelor Degree (Authenticated)') }}</span>
                                            <button class="btn btn-sm btn-outline-secondary">{{ __('View Template') }}</button>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="las la-check-circle text-success mr-2"></i> {{ __('Good Standing Certificate') }}</span>
                                            <span class="badge badge-warning">{{ __('Expires in 6 months') }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="las la-check-circle text-success mr-2"></i> {{ __('DataFlow Verification Report') }}</span>
                                            <a href="https://corp.dataflowgroup.com/" target="_blank" class="text-teal-600 font-weight-bold" style="color: #0d9488;">{{ __('Go to DataFlow') }} <i class="las la-external-link-alt"></i></a>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="las la-check-circle text-success mr-2"></i> {{ __('Police Clearance Certificate') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Hours Tab -->
                        <div class="tab-pane fade" id="v-pills-hours" role="tabpanel" aria-labelledby="v-pills-hours-tab">
                            <div class="card shadow-sm border-0">
                                <div class="card-body p-4">
                                    <h4 class="text-teal-700 font-weight-bold mb-4" style="color: #0d9488;">{{ __('Curriculum & Credit Hours') }}</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="alert alert-light border-teal" style="border-left: 4px solid #0d9488;">
                                                <h6 class="font-weight-bold">{{ __('Minimum Total Hours') }}</h6>
                                                <p class="h3 text-teal-700 mb-0" style="color: #0d9488;">4,500 {{ __('Hours') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="alert alert-light border-teal" style="border-left: 4px solid #0d9488;">
                                                <h6 class="font-weight-bold">{{ __('Clinical Training') }}</h6>
                                                <p class="h3 text-teal-700 mb-0" style="color: #0d9488;">1,000 {{ __('Hours') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5 class="font-weight-bold mt-4">{{ __('Hours Calculator') }}</h5>
                                    <p class="text-muted">{{ __('Enter your transcript hours to check eligibility.') }}</p>
                                    <div class="form-row align-items-end">
                                        <div class="col">
                                            <input type="number" class="form-control" placeholder="{{ __('Your Total Hours') }}">
                                        </div>
                                        <div class="col">
                                            <input type="number" class="form-control" placeholder="{{ __('Your Clinical Hours') }}">
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-teal text-white" style="background-color: #0d9488;">{{ __('Calculate') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Experience Tab -->
                        <div class="tab-pane fade" id="v-pills-experience" role="tabpanel" aria-labelledby="v-pills-experience-tab">
                            <div class="card shadow-sm border-0">
                                <div class="card-body p-4">
                                    <h4 class="text-teal-700 font-weight-bold mb-4" style="color: #0d9488;">{{ __('Experience & Internship') }}</h4>
                                    <div class="media mb-4">
                                        <div class="media-body">
                                            <h5 class="mt-0 font-weight-bold">{{ __('Mandatory Internship') }}</h5>
                                            <p>{{ __('Must complete a 12-month rotating internship including Orthopedics, Neurology, Pediatrics, and ICU.') }}</p>
                                        </div>
                                    </div>
                                    <div class="media mb-4">
                                        <div class="media-body">
                                            <h5 class="mt-0 font-weight-bold">{{ __('Post-Licensure Experience') }}</h5>
                                            <p>{{ __('Minimum 2 years of full-time work experience in a recognized hospital or clinic after obtaining the home country license.') }}</p>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-teal" role="progressbar" style="width: 100%; background-color: #0d9488;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">{{ __('2 Years Required') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Exams Tab -->
                        <div class="tab-pane fade" id="v-pills-exams" role="tabpanel" aria-labelledby="v-pills-exams-tab">
                            <div class="card shadow-sm border-0">
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h4 class="text-teal-700 font-weight-bold mb-4" style="color: #0d9488;">{{ __('Licensing Examination') }}</h4>
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row" class="bg-light">{{ __('Exam Name') }}</th>
                                                        <td>Prometric / HAAD / OMSB</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="bg-light">{{ __('Format') }}</th>
                                                        <td>{{ __('Computer Based Test (CBT)') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="bg-light">{{ __('Passing Score') }}</th>
                                                        <td>60%</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="bg-light">{{ __('Attempts Allowed') }}</th>
                                                        <td>3 {{ __('Attempts per year') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <a href="https://www.prometric.com/test-takers/search" target="_blank" class="btn btn-teal btn-block text-white" style="background-color: #0d9488;">{{ __('Register for Exam (Prometric)') }} <i class="las la-external-link-alt"></i></a>
                                        </div>
                                        <div class="col-md-4 border-left">
                                            <h6 class="text-center font-weight-bold mb-3">{{ __('Success Rate') }}</h6>
                                            <div class="text-center">
                                                 <div style="height: 200px; width: 100%;">
                                                    <!-- Simple CSS Bar Chart for visual punch -->
                                                    <div class="d-flex justify-content-center align-items-end h-100 pb-2">
                                                        <div class="mx-2 text-center">
                                                            <div class="bg-secondary rounded-top" style="width: 40px; height: 60%; opacity: 0.5;"></div>
                                                            <small class="d-block mt-1">{{ __('Global') }}</small>
                                                        </div>
                                                        <div class="mx-2 text-center">
                                                            <div class="bg-teal rounded-top" style="width: 40px; height: 85%; background-color: #0d9488;"></div>
                                                            <small class="d-block mt-1 font-weight-bold text-teal-700" style="color: #0d9488;">{{ __('Target') }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="small text-muted mt-2">{{ __('Pass rate for this exam pathway') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="text-center py-5">
            <i class="las la-globe-americas text-muted mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
            <h4 class="text-muted">{{ __('Please select both Source and Target countries to view requirements.') }}</h4>
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sourceSelect = document.getElementById('sourceCountry');
        const targetSelect = document.getElementById('targetCountry');
        const requirementsSection = document.getElementById('requirementsSection');
        const emptyState = document.getElementById('emptyState');

        function checkSelections() {
            if (sourceSelect.value && targetSelect.value) {
                requirementsSection.style.display = 'block';
                emptyState.style.display = 'none';

                // Scroll to results smoothly
                requirementsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                requirementsSection.style.display = 'none';
                emptyState.style.display = 'block';
            }
        }

        sourceSelect.addEventListener('change', checkSelections);
        targetSelect.addEventListener('change', checkSelections);
    });
</script>
<style>
    .nav-pills .nav-link {
        color: #495057;
        border-radius: 0.5rem;
        transition: all 0.3s;
    }
    .nav-pills .nav-link.active {
        background-color: #e0f2f1;
        color: #00695c !important; 
        border-right: 4px solid #00695c;
    }
    .btn-teal {
        background-color: #0d9488;
        border-color: #0d9488;
    }
    .btn-teal:hover {
         background-color: #0f766e;
         border-color: #0f766e;
    }
    .border-teal {
        border-color: #0d9488 !important;
    }
</style>
@endpush
@endsection
