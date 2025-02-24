<?php

$pageTitle = "Finance Overview";
$pageStyles = ["./finance.css"];
$sidebarActive = 5;
$menuBarConfig = [
    "title" => $pageTitle
];


require_once "../pageconfig.php";

require_once "../../includes/header.php";
require_once "../../includes/sidebar.php";

require_once "../../../auth-guards.php";
auth_required_guard("admin", "/staff/login");
?>

<main>
    <div class="staff-base-container">
        <?php require_once "../../includes/menubar.php"; ?>
        <div style="display: flex; flex-direction: column">
            <div style="display: flex; flex-direction: row">
                <div class="overview-small-box" style="padding: 20px; display: flex; flex-direction: column; align-items: center">
                    <h1>Income Overview</h1>
                    <div class="tab">
                        <button class="tablinks" onclick="openTab(event, 'CurrentMonth')">Current Month</button>
                        <button class="tablinks" onclick="openTab(event, 'LastMonth')">Last Month</button>
                        <button class="tablinks" onclick="openTab(event, 'TwoMonthsAgo')">Two Months Ago</button>
                    </div>

                    <div id="CurrentMonth" class="tabcontent">
                        <h3>November</h3>
                        <h1 style="font-size: 64px; margin: 50px">Rs. 125,000</h1>
                        <p>Number of membership plans sold: 68</p>
                    </div>

                    <div id="LastMonth" class="tabcontent">
                        <h3>October</h3>
                        <h1 style="font-size: 64px; margin: 50px">Rs. 94,000</h1>
                        <p>Number of membership plans sold: 63</p>
                    </div>

                    <div id="TwoMonthsAgo" class="tabcontent">
                        <h3>September</h3>
                        <h1 style="font-size: 64px; margin: 50px">Rs. 132,000</h1>
                        <p>Number of membership plans sold: 69</p>
                    </div>
                </div>
                <div class="overview-small-box" style="padding: 20px; display: flex; flex-direction: column; align-items: center">
                    <h1>Income Growth Analysis</h1>
                    <svg class='container' width="529px" height="286px" viewBox="30 27 529 286" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <!--https://codepen.io/Lunnaris/pen/vKWvQN-->
                        <g id="graph-copy" stroke="#52525b" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(30.000000, 27.000000)">
                            <g id="y_axis" font-size="11.0833333" font-family=".HelveticaNeueDeskInterface-Regular, .Helvetica Neue DeskInterface" fill="#FFFFFF" opacity="0.4" font-weight="normal">
                                <text id="0">
                                    <tspan x="25.3008249" y="264.333333">0</tspan>
                                </text>
                                <text id="200">
                                    <tspan x="12.7757572" y="232.666667">20k</tspan>
                                </text>
                                <text id="400">
                                    <tspan x="12.7757572" y="201">40k</tspan>
                                </text>
                                <text id="600">
                                    <tspan x="12.7757572" y="169.333333">60k</tspan>
                                </text>
                                <text id="800">
                                    <tspan x="12.7757572" y="137.666667">80k</tspan>
                                </text>
                                <text id="1000">
                                    <tspan x="6.51322328" y="106">100k</tspan>
                                </text>
                                <text id="1200">
                                    <tspan x="6.51322328" y="74.3333333">120k</tspan>
                                </text>
                                <text id="1400">
                                    <tspan x="6.51322328" y="42.6666667">140k</tspan>
                                </text>
                                <text id="1600">
                                    <tspan x="6.51322328" y="11">160k</tspan>
                                </text>
                            </g>
                            <g id="GRAPHS" transform="translate(64.000000, 16.000000)" stroke-linecap="round" stroke-width="8" stroke-linejoin="round">
                                <polyline id="Income" stroke="#8e33ff" points="0 1 50 1 100 50 150 50 200 100 250 50 300 50 350 100 400 50 450 50"></polyline>
                            </g>
                            <g id="x_axis" transform="translate(71.974046, 271.541667)" font-size="11.0833333" font-family=".HelveticaNeueDeskInterface-Regular, .Helvetica Neue DeskInterface" fill="#FFFFFF" opacity="0.4" font-weight="normal">
                                <text id="1">
                                    <tspan x="0.396183206" y="11">1</tspan>
                                </text>
                                <text id="2">
                                    <tspan x="39.2603361" y="11">2</tspan>
                                </text>
                                <text id="3">
                                    <tspan x="78.8786567" y="11">3</tspan>
                                </text>
                                <text id="4">
                                    <tspan x="118.496977" y="11">4</tspan>
                                </text>
                                <text id="5">
                                    <tspan x="158.115298" y="11">5</tspan>
                                </text>
                                <text id="6">
                                    <tspan x="197.733619" y="11">6</tspan>
                                </text>
                                <text id="7">
                                    <tspan x="237.351939" y="11">7</tspan>
                                </text>
                                <text id="8">
                                    <tspan x="276.97026" y="11">8</tspan>
                                </text>
                                <text id="9">
                                    <tspan x="316.58858" y="11">9</tspan>
                                </text>
                                <text id="10">
                                    <tspan x="359.229833" y="11">10</tspan>
                                </text>
                                <text id="11">
                                    <tspan x="400.036703" y="11">11</tspan>
                                </text>
                                <text id="12">
                                    <tspan x="438.466474" y="11">12</tspan>
                                </text>
                            </g>
                            <!--                        <g id="grid" transform="translate(46.618321, 4.750000)" stroke="#52525b" stroke-linecap="square" opacity="0.0800000057">-->
                            <!--                            <path d="M0.396183206,1.1875 L478.991396,1.1875" id="Line"></path>-->
                            <!--                            <path d="M0.396183206,32.8541667 L478.991396,32.8541667" id="Line"></path>-->
                            <!--                            <path d="M0.396183206,64.5208333 L478.991396,64.5208333" id="Line"></path>-->
                            <!--                            <path d="M0.396183206,96.1875 L478.991396,96.1875" id="Line"></path>-->
                            <!--                            <path d="M0.396183206,127.854167 L478.991396,127.854167" id="Line"></path>-->
                            <!--                            <path d="M0.396183206,159.520833 L478.991396,159.520833" id="Line"></path>-->
                            <!--                            <path d="M0.396183206,191.1875 L478.991396,191.1875" id="Line"></path>-->
                            <!--                            <path d="M0.396183206,222.854167 L478.991396,222.854167" id="Line"></path>-->
                            <!--                            <path d="M0.396183206,254.520833 L478.991396,254.520833" id="Line"></path>-->
                            <!--                        </g>-->
                        </g>
                    </svg>
                </div>
            </div>
            <div class="overview-small-box" style="padding: 20px; width: 98%">
                <div class="container">
                    <h2>Membership Plan Sales</h2>
                    <ul class="responsive-table">
                        <li class="table-header">
                            <div class="col col-1">Membership Name</div>
                            <div class="col col-2">Number of Sales</div>
                            <div class="col col-3">Total Income</div>
                        </li>
                        <li class="table-row">
                            <div class="col col-1" data-label="Membership Name">Basic Plan</div>
                            <div class="col col-2" data-label="Number of Sales">120</div>
                            <div class="col col-3" data-label="Total Income">Rs. 45,600</div>
                        </li>
                        <li class="table-row">
                            <div class="col col-1" data-label="Membership Name">Standard Plan</div>
                            <div class="col col-2" data-label="Number of Sales">80</div>
                            <div class="col col-3" data-label="Total Income">Rs. 32,000</div>
                        </li>
                        <li class="table-row">
                            <div class="col col-1" data-label="Membership Name">Premium Plan</div>
                            <div class="col col-2" data-label="Number of Sales">50</div>
                            <div class="col col-3" data-label="Total Income">Rs. 25,000</div>
                        </li>
                        <li class="table-row">
                            <div class="col col-1" data-label="Membership Name">VIP Plan</div>
                            <div class="col col-2" data-label="Number of Sales">30</div>
                            <div class="col col-3" data-label="Total Income">Rs. 67,000</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once "../../includes/footer.php"; ?>

<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Open the default tab
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector(".tablinks").click();
    });
</script>