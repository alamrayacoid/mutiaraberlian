@extends('main')

@section('content')
<article class="content dashboard-page">
    <h3>Selamat Datang !</h3>
    <!-- <section class="section">
        <div class="row sameheight-container">
            <div class="col col-12 col-sm-12 col-md-6 col-xl-5 stats-col">
                <div class="card sameheight-item stats" data-exclude="xs">
                    <div class="card-block">
                        <div class="title-block">
                            <h4 class="title"> Stats </h4>
                            <p class="title-description"> Website metrics for
                                <a href="http://modularteam.github.io/modularity-free-admin-dashboard-theme-html/"> your awesome project </a>
                            </p>
                        </div>
                        <div class="row row-sm stats-container">
                            <div class="col-12 col-sm-6 stat-col">
                                <div class="stat-icon">
                                    <i class="fa fa-rocket"></i>
                                </div>
                                <div class="stat">
                                    <div class="value"> 5407 </div>
                                    <div class="name"> Active items </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 75%;"></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 stat-col">
                                <div class="stat-icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <div class="stat">
                                    <div class="value"> 78464 </div>
                                    <div class="name"> Items sold </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 25%;"></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6  stat-col">
                                <div class="stat-icon">
                                    <i class="fa fa-line-chart"></i>
                                </div>
                                <div class="stat">
                                    <div class="value"> $80.560 </div>
                                    <div class="name"> Monthly income </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 60%;"></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6  stat-col">
                                <div class="stat-icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="stat">
                                    <div class="value"> 359 </div>
                                    <div class="name"> Total users </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 34%;"></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6  stat-col">
                                <div class="stat-icon">
                                    <i class="fa fa-list-alt"></i>
                                </div>
                                <div class="stat">
                                    <div class="value"> 59 </div>
                                    <div class="name"> Tickets closed </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 49%;"></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 stat-col">
                                <div class="stat-icon">
                                    <i class="fa fa-dollar"></i>
                                </div>
                                <div class="stat">
                                    <div class="value"> $780.064 </div>
                                    <div class="name"> Total income </div>
                                </div>
                                <div class="progress stat-progress">
                                    <div class="progress-bar" style="width: 15%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-12 col-sm-12 col-md-6 col-xl-7 history-col">
                <div class="card sameheight-item" data-exclude="xs" id="dashboard-history">
                    <div class="card-header card-header-sm bordered">
                        <div class="header-block">
                            <h3 class="title">History</h3>
                        </div>
                        <ul class="nav nav-tabs pull-right" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#visits" role="tab" data-toggle="tab">Visits</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#downloads" role="tab" data-toggle="tab">Downloads</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-block">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active fade show" id="visits">
                                <p class="title-description"> Number of unique visits last 30 days </p>
                                <div id="dashboard-visits-chart"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="downloads">
                                <p class="title-description"> Number of downloads last 30 days </p>
                                <div id="dashboard-downloads-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="row sameheight-container">
            <div class="col-xl-8">
                <div class="card sameheight-item items" data-exclude="xs,sm,lg">
                    <div class="card-header bordered">
                        <div class="header-block">
                            <h3 class="title"> Items </h3>
                            <a href="#" class="btn btn-primary btn-sm"> Add new </a>
                        </div>
                        <div class="header-block pull-right">
                            <label class="search">
                                <input class="search-input" placeholder="search...">
                                <i class="fa fa-search search-icon"></i>
                            </label>
                            <div class="pagination">
                                <a href="" class="btn btn-primary btn-sm">
                                    <i class="fa fa-angle-up"></i>
                                </a>
                                <a href="" class="btn btn-primary btn-sm">
                                    <i class="fa fa-angle-down"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <ul class="item-list striped">
                        <li class="item item-list-header">
                            <div class="item-row">
                                <div class="item-col item-col-header fixed item-col-img xs"></div>
                                <div class="item-col item-col-header item-col-title">
                                    <div>
                                        <span>Name</span>
                                    </div>
                                </div>
                                <div class="item-col item-col-header item-col-sales">
                                    <div>
                                        <span>Sales</span>
                                    </div>
                                </div>
                                <div class="item-col item-col-header item-col-stats">
                                    <div class="no-overflow">
                                        <span>Stats</span>
                                    </div>
                                </div>
                                <div class="item-col item-col-header item-col-date">
                                    <div>
                                        <span>Published</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="item">
                            <div class="item-row">
                                <div class="item-col fixed item-col-img xs">
                                    <a href="">
                                        <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg)"></div>
                                    </a>
                                </div>
                                <div class="item-col item-col-title no-overflow">
                                    <div>
                                        <a href="" class="">
                                            <h4 class="item-title no-wrap"> 12 Myths Uncovered About IT &amp; Software </h4>
                                        </a>
                                    </div>
                                </div>
                                <div class="item-col item-col-sales">
                                    <div class="item-heading">Sales</div>
                                    <div> 4958 </div>
                                </div>
                                <div class="item-col item-col-stats">
                                    <div class="item-heading">Stats</div>
                                    <div class="no-overflow">
                                        <div class="item-stats sparkline" data-type="bar"></div>
                                    </div>
                                </div>
                                <div class="item-col item-col-date">
                                    <div class="item-heading">Published</div>
                                    <div> 21 SEP 10:45 </div>
                                </div>
                            </div>
                        </li>
                        <li class="item">
                            <div class="item-row">
                                <div class="item-col fixed item-col-img xs">
                                    <a href="">
                                        <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/_everaldo/128.jpg)"></div>
                                    </a>
                                </div>
                                <div class="item-col item-col-title no-overflow">
                                    <div>
                                        <a href="" class="">
                                            <h4 class="item-title no-wrap"> 50% of things doesn&#x27;t really belongs to you </h4>
                                        </a>
                                    </div>
                                </div>
                                <div class="item-col item-col-sales">
                                    <div class="item-heading">Sales</div>
                                    <div> 192 </div>
                                </div>
                                <div class="item-col item-col-stats">
                                    <div class="item-heading">Stats</div>
                                    <div class="no-overflow">
                                        <div class="item-stats sparkline" data-type="bar"></div>
                                    </div>
                                </div>
                                <div class="item-col item-col-date">
                                    <div class="item-heading">Published</div>
                                    <div> 21 SEP 10:45 </div>
                                </div>
                            </div>
                        </li>
                        <li class="item">
                            <div class="item-row">
                                <div class="item-col fixed item-col-img xs">
                                    <a href="">
                                        <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/eduardo_olv/128.jpg)"></div>
                                    </a>
                                </div>
                                <div class="item-col item-col-title no-overflow">
                                    <div>
                                        <a href="" class="">
                                            <h4 class="item-title no-wrap"> Vestibulum tincidunt amet laoreet mauris sit sem aliquam cras maecenas vel aliquam. </h4>
                                        </a>
                                    </div>
                                </div>
                                <div class="item-col item-col-sales">
                                    <div class="item-heading">Sales</div>
                                    <div> 2143 </div>
                                </div>
                                <div class="item-col item-col-stats">
                                    <div class="item-heading">Stats</div>
                                    <div class="no-overflow">
                                        <div class="item-stats sparkline" data-type="bar"></div>
                                    </div>
                                </div>
                                <div class="item-col item-col-date">
                                    <div class="item-heading">Published</div>
                                    <div> 21 SEP 10:45 </div>
                                </div>
                            </div>
                        </li>
                        <li class="item">
                            <div class="item-row">
                                <div class="item-col fixed item-col-img xs">
                                    <a href="">
                                        <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/why_this/128.jpg)"></div>
                                    </a>
                                </div>
                                <div class="item-col item-col-title no-overflow">
                                    <div>
                                        <a href="" class="">
                                            <h4 class="item-title no-wrap"> 10 tips of Object Oriented Design </h4>
                                        </a>
                                    </div>
                                </div>
                                <div class="item-col item-col-sales">
                                    <div class="item-heading">Sales</div>
                                    <div> 124 </div>
                                </div>
                                <div class="item-col item-col-stats">
                                    <div class="item-heading">Stats</div>
                                    <div class="no-overflow">
                                        <div class="item-stats sparkline" data-type="bar"></div>
                                    </div>
                                </div>
                                <div class="item-col item-col-date">
                                    <div class="item-heading">Published</div>
                                    <div> 21 SEP 10:45 </div>
                                </div>
                            </div>
                        </li>
                        <li class="item">
                            <div class="item-row">
                                <div class="item-col fixed item-col-img xs">
                                    <a href="">
                                        <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/w7download/128.jpg)"></div>
                                    </a>
                                </div>
                                <div class="item-col item-col-title no-overflow">
                                    <div>
                                        <a href="" class="">
                                            <h4 class="item-title no-wrap"> Sometimes friend tells it is cold </h4>
                                        </a>
                                    </div>
                                </div>
                                <div class="item-col item-col-sales">
                                    <div class="item-heading">Sales</div>
                                    <div> 10214 </div>
                                </div>
                                <div class="item-col item-col-stats">
                                    <div class="item-heading">Stats</div>
                                    <div class="no-overflow">
                                        <div class="item-stats sparkline" data-type="bar"></div>
                                    </div>
                                </div>
                                <div class="item-col item-col-date">
                                    <div class="item-heading">Published</div>
                                    <div> 21 SEP 10:45 </div>
                                </div>
                            </div>
                        </li>
                        <li class="item">
                            <div class="item-row">
                                <div class="item-col fixed item-col-img xs">
                                    <a href="">
                                        <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/pankogut/128.jpg)"></div>
                                    </a>
                                </div>
                                <div class="item-col item-col-title no-overflow">
                                    <div>
                                        <a href="" class="">
                                            <h4 class="item-title no-wrap"> New ways of conceptual thinking </h4>
                                        </a>
                                    </div>
                                </div>
                                <div class="item-col item-col-sales">
                                    <div class="item-heading">Sales</div>
                                    <div> 3217 </div>
                                </div>
                                <div class="item-col item-col-stats">
                                    <div class="item-heading">Stats</div>
                                    <div class="no-overflow">
                                        <div class="item-stats sparkline" data-type="bar"></div>
                                    </div>
                                </div>
                                <div class="item-col item-col-date">
                                    <div class="item-heading">Published</div>
                                    <div> 21 SEP 10:45 </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card sameheight-item sales-breakdown" data-exclude="xs,sm,lg">
                    <div class="card-header">
                        <div class="header-block">
                            <h3 class="title"> Sales breakdown </h3>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="dashboard-sales-breakdown-chart" id="dashboard-sales-breakdown-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="row sameheight-container">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header">
                        <div class="header-block">
                            Datatables
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="table-flip-scroll">
                            <table class="table table-hover data-table flip-content" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Tiger Nixon</td>
                                        <td>System Architect</td>
                                        <td>Edinburgh</td>
                                        <td>61</td>
                                        <td>2011/04/25</td>
                                        <td>$320,800</td>
                                    </tr>
                                    <tr>
                                        <td>Garrett Winters</td>
                                        <td>Accountant</td>
                                        <td>Tokyo</td>
                                        <td>63</td>
                                        <td>2011/07/25</td>
                                        <td>$170,750</td>
                                    </tr>
                                    <tr>
                                        <td>Ashton Cox</td>
                                        <td>Junior Technical Author</td>
                                        <td>San Francisco</td>
                                        <td>66</td>
                                        <td>2009/01/12</td>
                                        <td>$86,000</td>
                                    </tr>
                                    <tr>
                                        <td>Cedric Kelly</td>
                                        <td>Senior Javascript Developer</td>
                                        <td>Edinburgh</td>
                                        <td>22</td>
                                        <td>2012/03/29</td>
                                        <td>$433,060</td>
                                    </tr>
                                    <tr>
                                        <td>Airi Satou</td>
                                        <td>Accountant</td>
                                        <td>Tokyo</td>
                                        <td>33</td>
                                        <td>2008/11/28</td>
                                        <td>$162,700</td>
                                    </tr>
                                    <tr>
                                        <td>Brielle Williamson</td>
                                        <td>Integration Specialist</td>
                                        <td>New York</td>
                                        <td>61</td>
                                        <td>2012/12/02</td>
                                        <td>$372,000</td>
                                    </tr>
                                    <tr>
                                        <td>Herrod Chandler</td>
                                        <td>Sales Assistant</td>
                                        <td>San Francisco</td>
                                        <td>59</td>
                                        <td>2012/08/06</td>
                                        <td>$137,500</td>
                                    </tr>
                                    <tr>
                                        <td>Rhona Davidson</td>
                                        <td>Integration Specialist</td>
                                        <td>Tokyo</td>
                                        <td>55</td>
                                        <td>2010/10/14</td>
                                        <td>$327,900</td>
                                    </tr>
                                    <tr>
                                        <td>Colleen Hurst</td>
                                        <td>Javascript Developer</td>
                                        <td>San Francisco</td>
                                        <td>39</td>
                                        <td>2009/09/15</td>
                                        <td>$205,500</td>
                                    </tr>
                                    <tr>
                                        <td>Sonya Frost</td>
                                        <td>Software Engineer</td>
                                        <td>Edinburgh</td>
                                        <td>23</td>
                                        <td>2008/12/13</td>
                                        <td>$103,600</td>
                                    </tr>
                                    <tr>
                                        <td>Jena Gaines</td>
                                        <td>Office Manager</td>
                                        <td>London</td>
                                        <td>30</td>
                                        <td>2008/12/19</td>
                                        <td>$90,560</td>
                                    </tr>
                                    <tr>
                                        <td>Quinn Flynn</td>
                                        <td>Support Lead</td>
                                        <td>Edinburgh</td>
                                        <td>22</td>
                                        <td>2013/03/03</td>
                                        <td>$342,000</td>
                                    </tr>
                                    <tr>
                                        <td>Charde Marshall</td>
                                        <td>Regional Director</td>
                                        <td>San Francisco</td>
                                        <td>36</td>
                                        <td>2008/10/16</td>
                                        <td>$470,600</td>
                                    </tr>
                                    <tr>
                                        <td>Haley Kennedy</td>
                                        <td>Senior Marketing Designer</td>
                                        <td>London</td>
                                        <td>43</td>
                                        <td>2012/12/18</td>
                                        <td>$313,500</td>
                                    </tr>
                                    <tr>
                                        <td>Tatyana Fitzpatrick</td>
                                        <td>Regional Director</td>
                                        <td>London</td>
                                        <td>19</td>
                                        <td>2010/03/17</td>
                                        <td>$385,750</td>
                                    </tr>
                                    <tr>
                                        <td>Michael Silva</td>
                                        <td>Marketing Designer</td>
                                        <td>London</td>
                                        <td>66</td>
                                        <td>2012/11/27</td>
                                        <td>$198,500</td>
                                    </tr>
                                    <tr>
                                        <td>Paul Byrd</td>
                                        <td>Chief Financial Officer (CFO)</td>
                                        <td>New York</td>
                                        <td>64</td>
                                        <td>2010/06/09</td>
                                        <td>$725,000</td>
                                    </tr>
                                    <tr>
                                        <td>Gloria Little</td>
                                        <td>Systems Administrator</td>
                                        <td>New York</td>
                                        <td>59</td>
                                        <td>2009/04/10</td>
                                        <td>$237,500</td>
                                    </tr>
                                    <tr>
                                        <td>Bradley Greer</td>
                                        <td>Software Engineer</td>
                                        <td>London</td>
                                        <td>41</td>
                                        <td>2012/10/13</td>
                                        <td>$132,000</td>
                                    </tr>
                                    <tr>
                                        <td>Dai Rios</td>
                                        <td>Personnel Lead</td>
                                        <td>Edinburgh</td>
                                        <td>35</td>
                                        <td>2012/09/26</td>
                                        <td>$217,500</td>
                                    </tr>
                                    <tr>
                                        <td>Jenette Caldwell</td>
                                        <td>Development Lead</td>
                                        <td>New York</td>
                                        <td>30</td>
                                        <td>2011/09/03</td>
                                        <td>$345,000</td>
                                    </tr>
                                    <tr>
                                        <td>Yuri Berry</td>
                                        <td>Chief Marketing Officer (CMO)</td>
                                        <td>New York</td>
                                        <td>40</td>
                                        <td>2009/06/25</td>
                                        <td>$675,000</td>
                                    </tr>
                                    <tr>
                                        <td>Caesar Vance</td>
                                        <td>Pre-Sales Support</td>
                                        <td>New York</td>
                                        <td>21</td>
                                        <td>2011/12/12</td>
                                        <td>$106,450</td>
                                    </tr>
                                    <tr>
                                        <td>Doris Wilder</td>
                                        <td>Sales Assistant</td>
                                        <td>Sidney</td>
                                        <td>23</td>
                                        <td>2010/09/20</td>
                                        <td>$85,600</td>
                                    </tr>
                                    <tr>
                                        <td>Angelica Ramos</td>
                                        <td>Chief Executive Officer (CEO)</td>
                                        <td>London</td>
                                        <td>47</td>
                                        <td>2009/10/09</td>
                                        <td>$1,200,000</td>
                                    </tr>
                                    <tr>
                                        <td>Gavin Joyce</td>
                                        <td>Developer</td>
                                        <td>Edinburgh</td>
                                        <td>42</td>
                                        <td>2010/12/22</td>
                                        <td>$92,575</td>
                                    </tr>
                                    <tr>
                                        <td>Jennifer Chang</td>
                                        <td>Regional Director</td>
                                        <td>Singapore</td>
                                        <td>28</td>
                                        <td>2010/11/14</td>
                                        <td>$357,650</td>
                                    </tr>
                                    <tr>
                                        <td>Brenden Wagner</td>
                                        <td>Software Engineer</td>
                                        <td>San Francisco</td>
                                        <td>28</td>
                                        <td>2011/06/07</td>
                                        <td>$206,850</td>
                                    </tr>
                                    <tr>
                                        <td>Fiona Green</td>
                                        <td>Chief Operating Officer (COO)</td>
                                        <td>San Francisco</td>
                                        <td>48</td>
                                        <td>2010/03/11</td>
                                        <td>$850,000</td>
                                    </tr>
                                    <tr>
                                        <td>Shou Itou</td>
                                        <td>Regional Marketing</td>
                                        <td>Tokyo</td>
                                        <td>20</td>
                                        <td>2011/08/14</td>
                                        <td>$163,000</td>
                                    </tr>
                                    <tr>
                                        <td>Michelle House</td>
                                        <td>Integration Specialist</td>
                                        <td>Sidney</td>
                                        <td>37</td>
                                        <td>2011/06/02</td>
                                        <td>$95,400</td>
                                    </tr>
                                    <tr>
                                        <td>Suki Burks</td>
                                        <td>Developer</td>
                                        <td>London</td>
                                        <td>53</td>
                                        <td>2009/10/22</td>
                                        <td>$114,500</td>
                                    </tr>
                                    <tr>
                                        <td>Prescott Bartlett</td>
                                        <td>Technical Author</td>
                                        <td>London</td>
                                        <td>27</td>
                                        <td>2011/05/07</td>
                                        <td>$145,000</td>
                                    </tr>
                                    <tr>
                                        <td>Gavin Cortez</td>
                                        <td>Team Leader</td>
                                        <td>San Francisco</td>
                                        <td>22</td>
                                        <td>2008/10/26</td>
                                        <td>$235,500</td>
                                    </tr>
                                    <tr>
                                        <td>Martena Mccray</td>
                                        <td>Post-Sales support</td>
                                        <td>Edinburgh</td>
                                        <td>46</td>
                                        <td>2011/03/09</td>
                                        <td>$324,050</td>
                                    </tr>
                                    <tr>
                                        <td>Unity Butler</td>
                                        <td>Marketing Designer</td>
                                        <td>San Francisco</td>
                                        <td>47</td>
                                        <td>2009/12/09</td>
                                        <td>$85,675</td>
                                    </tr>
                                    <tr>
                                        <td>Howard Hatfield</td>
                                        <td>Office Manager</td>
                                        <td>San Francisco</td>
                                        <td>51</td>
                                        <td>2008/12/16</td>
                                        <td>$164,500</td>
                                    </tr>
                                    <tr>
                                        <td>Hope Fuentes</td>
                                        <td>Secretary</td>
                                        <td>San Francisco</td>
                                        <td>41</td>
                                        <td>2010/02/12</td>
                                        <td>$109,850</td>
                                    </tr>
                                    <tr>
                                        <td>Vivian Harrell</td>
                                        <td>Financial Controller</td>
                                        <td>San Francisco</td>
                                        <td>62</td>
                                        <td>2009/02/14</td>
                                        <td>$452,500</td>
                                    </tr>
                                    <tr>
                                        <td>Timothy Mooney</td>
                                        <td>Office Manager</td>
                                        <td>London</td>
                                        <td>37</td>
                                        <td>2008/12/11</td>
                                        <td>$136,200</td>
                                    </tr>
                                    <tr>
                                        <td>Jackson Bradshaw</td>
                                        <td>Director</td>
                                        <td>New York</td>
                                        <td>65</td>
                                        <td>2008/09/26</td>
                                        <td>$645,750</td>
                                    </tr>
                                    <tr>
                                        <td>Olivia Liang</td>
                                        <td>Support Engineer</td>
                                        <td>Singapore</td>
                                        <td>64</td>
                                        <td>2011/02/03</td>
                                        <td>$234,500</td>
                                    </tr>
                                    <tr>
                                        <td>Bruno Nash</td>
                                        <td>Software Engineer</td>
                                        <td>London</td>
                                        <td>38</td>
                                        <td>2011/05/03</td>
                                        <td>$163,500</td>
                                    </tr>
                                    <tr>
                                        <td>Sakura Yamamoto</td>
                                        <td>Support Engineer</td>
                                        <td>Tokyo</td>
                                        <td>37</td>
                                        <td>2009/08/19</td>
                                        <td>$139,575</td>
                                    </tr>
                                    <tr>
                                        <td>Thor Walton</td>
                                        <td>Developer</td>
                                        <td>New York</td>
                                        <td>61</td>
                                        <td>2013/08/11</td>
                                        <td>$98,540</td>
                                    </tr>
                                    <tr>
                                        <td>Finn Camacho</td>
                                        <td>Support Engineer</td>
                                        <td>San Francisco</td>
                                        <td>47</td>
                                        <td>2009/07/07</td>
                                        <td>$87,500</td>
                                    </tr>
                                    <tr>
                                        <td>Serge Baldwin</td>
                                        <td>Data Coordinator</td>
                                        <td>Singapore</td>
                                        <td>64</td>
                                        <td>2012/04/09</td>
                                        <td>$138,575</td>
                                    </tr>
                                    <tr>
                                        <td>Zenaida Frank</td>
                                        <td>Software Engineer</td>
                                        <td>New York</td>
                                        <td>63</td>
                                        <td>2010/01/04</td>
                                        <td>$125,250</td>
                                    </tr>
                                    <tr>
                                        <td>Zorita Serrano</td>
                                        <td>Software Engineer</td>
                                        <td>San Francisco</td>
                                        <td>56</td>
                                        <td>2012/06/01</td>
                                        <td>$115,000</td>
                                    </tr>
                                    <tr>
                                        <td>Jennifer Acosta</td>
                                        <td>Junior Javascript Developer</td>
                                        <td>Edinburgh</td>
                                        <td>43</td>
                                        <td>2013/02/01</td>
                                        <td>$75,650</td>
                                    </tr>
                                    <tr>
                                        <td>Cara Stevens</td>
                                        <td>Sales Assistant</td>
                                        <td>New York</td>
                                        <td>46</td>
                                        <td>2011/12/06</td>
                                        <td>$145,600</td>
                                    </tr>
                                    <tr>
                                        <td>Hermione Butler</td>
                                        <td>Regional Director</td>
                                        <td>London</td>
                                        <td>47</td>
                                        <td>2011/03/21</td>
                                        <td>$356,250</td>
                                    </tr>
                                    <tr>
                                        <td>Lael Greer</td>
                                        <td>Systems Administrator</td>
                                        <td>London</td>
                                        <td>21</td>
                                        <td>2009/02/27</td>
                                        <td>$103,500</td>
                                    </tr>
                                    <tr>
                                        <td>Jonas Alexander</td>
                                        <td>Developer</td>
                                        <td>San Francisco</td>
                                        <td>30</td>
                                        <td>2010/07/14</td>
                                        <td>$86,500</td>
                                    </tr>
                                    <tr>
                                        <td>Shad Decker</td>
                                        <td>Regional Director</td>
                                        <td>Edinburgh</td>
                                        <td>51</td>
                                        <td>2008/11/13</td>
                                        <td>$183,000</td>
                                    </tr>
                                    <tr>
                                        <td>Michael Bruce</td>
                                        <td>Javascript Developer</td>
                                        <td>Singapore</td>
                                        <td>29</td>
                                        <td>2011/06/27</td>
                                        <td>$183,000</td>
                                    </tr>
                                    <tr>
                                        <td>Donna Snider</td>
                                        <td>Customer Support</td>
                                        <td>New York</td>
                                        <td>27</td>
                                        <td>2011/01/25</td>
                                        <td>$112,000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section map-tasks">
        <div class="row sameheight-container">
            <div class="col-md-8">
                <div class="card sameheight-item" data-exclude="xs,sm">
                    <div class="card-header">
                        <div class="header-block">
                            <h3 class="title"> Sales by countries </h3>
                        </div>
                    </div>
                    <div class="card-block">
                        <div id="dashboard-sales-map" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card tasks sameheight-item" data-exclude="xs,sm">
                    <div class="card-header bordered">
                        <div class="header-block">
                            <h3 class="title"> Tasks </h3>
                        </div>
                        <div class="header-block pull-right">
                            <a href="" class="btn btn-primary btn-sm rounded pull-right"> Add new </a>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="tasks-block">
                            <ul class="item-list">
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title">
                                            <label>
                                                <input class="checkbox" type="checkbox" checked="checked">
                                                <span>Meeting with embassador</span>
                                            </label>
                                        </div>
                                        <div class="item-col fixed item-col-actions-dropdown">
                                            <div class="item-actions-dropdown">
                                                <a class="item-actions-toggle-btn">
                                                    <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span>
                                                    <span class="active">
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    </span>
                                                </a>
                                                <div class="item-actions-block">
                                                    <ul class="item-actions-list">
                                                        <li>
                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
                                                                <i class="fa fa-trash-o "></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="check" href="#">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="edit" href="#">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title">
                                            <label>
                                                <input class="checkbox" type="checkbox" checked="checked">
                                                <span>Confession</span>
                                            </label>
                                        </div>
                                        <div class="item-col fixed item-col-actions-dropdown">
                                            <div class="item-actions-dropdown">
                                                <a class="item-actions-toggle-btn">
                                                    <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span>
                                                    <span class="active">
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    </span>
                                                </a>
                                                <div class="item-actions-block">
                                                    <ul class="item-actions-list">
                                                        <li>
                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
                                                                <i class="fa fa-trash-o "></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="check" href="#">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="edit" href="#">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title">
                                            <label>
                                                <input class="checkbox" type="checkbox">
                                                <span>Time to start building an ark</span>
                                            </label>
                                        </div>
                                        <div class="item-col fixed item-col-actions-dropdown">
                                            <div class="item-actions-dropdown">
                                                <a class="item-actions-toggle-btn">
                                                    <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span>
                                                    <span class="active">
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    </span>
                                                </a>
                                                <div class="item-actions-block">
                                                    <ul class="item-actions-list">
                                                        <li>
                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
                                                                <i class="fa fa-trash-o "></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="check" href="#">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="edit" href="#">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title">
                                            <label>
                                                <input class="checkbox" type="checkbox">
                                                <span>Beer time with dudes</span>
                                            </label>
                                        </div>
                                        <div class="item-col fixed item-col-actions-dropdown">
                                            <div class="item-actions-dropdown">
                                                <a class="item-actions-toggle-btn">
                                                    <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span>
                                                    <span class="active">
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    </span>
                                                </a>
                                                <div class="item-actions-block">
                                                    <ul class="item-actions-list">
                                                        <li>
                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
                                                                <i class="fa fa-trash-o "></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="check" href="#">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="edit" href="#">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title">
                                            <label>
                                                <input class="checkbox" type="checkbox" checked="checked">
                                                <span>Meeting new girls</span>
                                            </label>
                                        </div>
                                        <div class="item-col fixed item-col-actions-dropdown">
                                            <div class="item-actions-dropdown">
                                                <a class="item-actions-toggle-btn">
                                                    <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span>
                                                    <span class="active">
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    </span>
                                                </a>
                                                <div class="item-actions-block">
                                                    <ul class="item-actions-list">
                                                        <li>
                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
                                                                <i class="fa fa-trash-o "></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="check" href="#">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="edit" href="#">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title">
                                            <label>
                                                <input class="checkbox" type="checkbox">
                                                <span>Remember damned home address</span>
                                            </label>
                                        </div>
                                        <div class="item-col fixed item-col-actions-dropdown">
                                            <div class="item-actions-dropdown">
                                                <a class="item-actions-toggle-btn">
                                                    <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span>
                                                    <span class="active">
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    </span>
                                                </a>
                                                <div class="item-actions-block">
                                                    <ul class="item-actions-list">
                                                        <li>
                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
                                                                <i class="fa fa-trash-o "></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="check" href="#">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="edit" href="#">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title">
                                            <label>
                                                <input class="checkbox" type="checkbox">
                                                <span>Get home before you got sleep</span>
                                            </label>
                                        </div>
                                        <div class="item-col fixed item-col-actions-dropdown">
                                            <div class="item-actions-dropdown">
                                                <a class="item-actions-toggle-btn">
                                                    <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span>
                                                    <span class="active">
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    </span>
                                                </a>
                                                <div class="item-actions-block">
                                                    <ul class="item-actions-list">
                                                        <li>
                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
                                                                <i class="fa fa-trash-o "></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="check" href="#">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="edit" href="#">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title">
                                            <label>
                                                <input class="checkbox" type="checkbox" checked="checked">
                                                <span>Meeting with embassador</span>
                                            </label>
                                        </div>
                                        <div class="item-col fixed item-col-actions-dropdown">
                                            <div class="item-actions-dropdown">
                                                <a class="item-actions-toggle-btn">
                                                    <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span>
                                                    <span class="active">
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    </span>
                                                </a>
                                                <div class="item-actions-block">
                                                    <ul class="item-actions-list">
                                                        <li>
                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
                                                                <i class="fa fa-trash-o "></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="check" href="#">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="edit" href="#">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title">
                                            <label>
                                                <input class="checkbox" type="checkbox" checked="checked">
                                                <span>Confession</span>
                                            </label>
                                        </div>
                                        <div class="item-col fixed item-col-actions-dropdown">
                                            <div class="item-actions-dropdown">
                                                <a class="item-actions-toggle-btn">
                                                    <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span>
                                                    <span class="active">
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    </span>
                                                </a>
                                                <div class="item-actions-block">
                                                    <ul class="item-actions-list">
                                                        <li>
                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
                                                                <i class="fa fa-trash-o "></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="check" href="#">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="edit" href="#">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title">
                                            <label>
                                                <input class="checkbox" type="checkbox">
                                                <span>Time to start building an ark</span>
                                            </label>
                                        </div>
                                        <div class="item-col fixed item-col-actions-dropdown">
                                            <div class="item-actions-dropdown">
                                                <a class="item-actions-toggle-btn">
                                                    <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span>
                                                    <span class="active">
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    </span>
                                                </a>
                                                <div class="item-actions-block">
                                                    <ul class="item-actions-list">
                                                        <li>
                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
                                                                <i class="fa fa-trash-o "></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="check" href="#">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="edit" href="#">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title">
                                            <label>
                                                <input class="checkbox" type="checkbox">
                                                <span>Beer time with dudes</span>
                                            </label>
                                        </div>
                                        <div class="item-col fixed item-col-actions-dropdown">
                                            <div class="item-actions-dropdown">
                                                <a class="item-actions-toggle-btn">
                                                    <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span>
                                                    <span class="active">
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    </span>
                                                </a>
                                                <div class="item-actions-block">
                                                    <ul class="item-actions-list">
                                                        <li>
                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
                                                                <i class="fa fa-trash-o "></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="check" href="#">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="edit" href="#">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title">
                                            <label>
                                                <input class="checkbox" type="checkbox" checked="checked">
                                                <span>Meeting new girls</span>
                                            </label>
                                        </div>
                                        <div class="item-col fixed item-col-actions-dropdown">
                                            <div class="item-actions-dropdown">
                                                <a class="item-actions-toggle-btn">
                                                    <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span>
                                                    <span class="active">
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    </span>
                                                </a>
                                                <div class="item-actions-block">
                                                    <ul class="item-actions-list">
                                                        <li>
                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
                                                                <i class="fa fa-trash-o "></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="check" href="#">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="edit" href="#">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="item-row">
                                        <div class="item-col item-col-title">
                                            <label>
                                                <input class="checkbox" type="checkbox">
                                                <span>Remember damned home address</span>
                                            </label>
                                        </div>
                                        <div class="item-col fixed item-col-actions-dropdown">
                                            <div class="item-actions-dropdown">
                                                <a class="item-actions-toggle-btn">
                                                    <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span>
                                                    <span class="active">
                                                        <i class="fa fa-chevron-circle-right"></i>
                                                    </span>
                                                </a>
                                                <div class="item-actions-block">
                                                    <ul class="item-actions-list">
                                                        <li>
                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal">
                                                                <i class="fa fa-trash-o "></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="check" href="#">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="edit" href="#">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
</article>
@endsection
