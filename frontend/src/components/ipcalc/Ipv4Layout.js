import React from "react";
import axios from "axios";
import SubnetTableV4 from "./resultsTable/SubnetTableV4";
import SearchForm from "./inputForm/SearchForm";
import SmallerSubnetsV4 from "./resultsTable/smallerSubnet/SmallerSubnetsV4";


const OUTPUT_TABLE_DATA_V4 = {
    "network-subnet": {key: "Network subnet:", value: "192.168.170.0/24"},
    netmask: {key: "Netmask:", value: "255.255.255.0"},
    "network-address": {key: "Network address:", value: "192.168.170.0"},
    "first-address": {key: "First address:", value: "192.168.170.1"},
    "last-address": {key: "Last address:", value: "192.168.170.254"},
    "broadcast-address": {key: "Broadcast address:", value: "192.168.170.255"},
    "number-of-usable-address": {key: "Number of usable address:", value: 254},
    "nsx-cidr": {key: "NSX CIDR:", value: "192.168.170.1/24"},
    "nsx-static-ip-pool": {key: "NSX Static IP pool:", value: "192.168.170.2-192.168.170.254"},
};

const SMALLER_SUBNETS_V4 = {
    "25-subnet": {
        "00-value": {"cidr": 25, "subnet": "192.168.170.0/25"},
        "01-value": {"cidr": 25, "subnet": "192.168.170.128/25"}
    },
    "26-subnet": {
        "10-value": {"cidr": 26, "subnet": "192.168.170.0/26"},
        "11-value": {"cidr": 26, "subnet": "192.168.170.64/26"},
        "12-value": {"cidr": 26, "subnet": "192.168.170.128/26"},
        "13-value": {"cidr": 26, "subnet": "192.168.170.192/26"}
    },
    "27-subnet": {
        "20-value": {"cidr": 27, "subnet": "192.168.170.0/27"},
        "21-value": {"cidr": 27, "subnet": "192.168.170.32/27"},
        "22-value": {"cidr": 27, "subnet": "192.168.170.64/27"},
        "23-value": {"cidr": 27, "subnet": "192.168.170.96/27"},
        "24-value": {"cidr": 27, "subnet": "192.168.170.128/27"},
        "25-value": {"cidr": 27, "subnet": "192.168.170.160/27"},
        "26-value": {"cidr": 27, "subnet": "192.168.170.192/27"},
        "27-value": {"cidr": 27, "subnet": "192.168.170.224/27"}
    },
    "28-subnet": {
        "30-value": {"cidr": 28, "subnet": "192.168.170.0/28"},
        "31-value": {"cidr": 28, "subnet": "192.168.170.16/28"},
        "32-value": {"cidr": 28, "subnet": "192.168.170.32/28"},
        "33-value": {"cidr": 28, "subnet": "192.168.170.48/28"},
        "34-value": {"cidr": 28, "subnet": "192.168.170.64/28"},
        "35-value": {"cidr": 28, "subnet": "192.168.170.80/28"},
        "36-value": {"cidr": 28, "subnet": "192.168.170.96/28"},
        "37-value": {"cidr": 28, "subnet": "192.168.170.112/28"},
        "38-value": {"cidr": 28, "subnet": "192.168.170.128/28"},
        "39-value": {"cidr": 28, "subnet": "192.168.170.144/28"},
        "310-value": {"cidr": 28, "subnet": "192.168.170.160/28"},
        "311-value": {"cidr": 28, "subnet": "192.168.170.176/28"},
        "312-value": {"cidr": 28, "subnet": "192.168.170.192/28"},
        "313-value": {"cidr": 28, "subnet": "192.168.170.208/28"},
        "314-value": {"cidr": 28, "subnet": "192.168.170.224/28"},
        "315-value": {"cidr": 28, "subnet": "192.168.170.240/28"}
    },
    "29-subnet": {
        "40-value": {"cidr": 29, "subnet": "192.168.170.0/29"},
        "41-value": {"cidr": 29, "subnet": "192.168.170.8/29"},
        "42-value": {"cidr": 29, "subnet": "192.168.170.16/29"},
        "43-value": {"cidr": 29, "subnet": "192.168.170.24/29"},
        "44-value": {"cidr": 29, "subnet": "192.168.170.32/29"},
        "45-value": {"cidr": 29, "subnet": "192.168.170.40/29"},
        "46-value": {"cidr": 29, "subnet": "192.168.170.48/29"},
        "47-value": {"cidr": 29, "subnet": "192.168.170.56/29"},
        "48-value": {"cidr": 29, "subnet": "192.168.170.64/29"},
        "49-value": {"cidr": 29, "subnet": "192.168.170.72/29"},
        "410-value": {"cidr": 29, "subnet": "192.168.170.80/29"},
        "411-value": {"cidr": 29, "subnet": "192.168.170.88/29"},
        "412-value": {"cidr": 29, "subnet": "192.168.170.96/29"},
        "413-value": {"cidr": 29, "subnet": "192.168.170.104/29"},
        "414-value": {"cidr": 29, "subnet": "192.168.170.112/29"},
        "415-value": {"cidr": 29, "subnet": "192.168.170.120/29"},
        "416-value": {"cidr": 29, "subnet": "192.168.170.128/29"},
        "417-value": {"cidr": 29, "subnet": "192.168.170.136/29"},
        "418-value": {"cidr": 29, "subnet": "192.168.170.144/29"},
        "419-value": {"cidr": 29, "subnet": "192.168.170.152/29"},
        "420-value": {"cidr": 29, "subnet": "192.168.170.160/29"},
        "421-value": {"cidr": 29, "subnet": "192.168.170.168/29"},
        "422-value": {"cidr": 29, "subnet": "192.168.170.176/29"},
        "423-value": {"cidr": 29, "subnet": "192.168.170.184/29"},
        "424-value": {"cidr": 29, "subnet": "192.168.170.192/29"},
        "425-value": {"cidr": 29, "subnet": "192.168.170.200/29"},
        "426-value": {"cidr": 29, "subnet": "192.168.170.208/29"},
        "427-value": {"cidr": 29, "subnet": "192.168.170.216/29"},
        "428-value": {"cidr": 29, "subnet": "192.168.170.224/29"},
        "429-value": {"cidr": 29, "subnet": "192.168.170.232/29"},
        "430-value": {"cidr": 29, "subnet": "192.168.170.240/29"},
        "431-value": {"cidr": 29, "subnet": "192.168.170.248/29"}
    }
};

const API_URL = "http://localhost:8000";


class Ipv4Layout extends React.Component {

    constructor(props) {
        super(props);
        // must be initial
        this.state = {
            enteredSubnet: "",
            outputTableData: {},
            smallerSubnet: {},
        };
    }

    componentDidMount() {
        this.setState({
            enteredSubnet: "192.168.170.0/24",
            outputTableData: OUTPUT_TABLE_DATA_V4,
            smallerSubnet: SMALLER_SUBNETS_V4
        });
    }

    getApiSubnetInfo = (enteredSubnet = "172.20.30.0/24") => {
        axios
            .get(`${API_URL}/api/ipv4calc/${enteredSubnet}`)
            .then((res) => {
                console.log("Logging Axios data from api");
                console.log(res.data);
                this.setState({
                    enteredSubnet: enteredSubnet,
                    outputTableData: res.data,
                });
                console.log("Logging Axios data after api set");
                console.log(this.state);
            })
            .catch((error) => {
                console.log("Logging Axios Error");
                console.log(error);
                this.setState({
                    enteredSubnet: enteredSubnet,
                    outputTableData: OUTPUT_TABLE_DATA_V4,
                });
            });
    }

    getApiSmallerSubnets = (enteredSubnet = "10.22.33.0/24") => {
        axios
            .get(`${API_URL}/api/ipv4/smaller-subnets/${enteredSubnet}`)
            .then((res) => {
                console.log("Logging Axios data from api - getApiSmallerSubnets");
                console.log(res.data);
                this.setState({
                    enteredSubnet: enteredSubnet,
                    smallerSubnet: res.data,
                });
                console.log("Logging Axios data after api set - getApiSmallerSubnets");
                console.log(this.state);
            })
            .catch((error) => {
                console.log("Logging Axios Error");
                console.log(error);
                this.setState({
                    enteredSubnet: enteredSubnet,
                    smallerSubnet: SMALLER_SUBNETS_V4,
                });
            });
    }


    /**
     * Here I have output from web-inputForm
     * @param {*} enteredSubnet
     */
    submittedSubnetFormHandler = (enteredSubnet) => {
        if (enteredSubnet.trim().length < 4) {
            return;
        }
        this.getApiSubnetInfo(enteredSubnet);
        this.getApiSmallerSubnets(enteredSubnet);
    };

    smallerSubnetClickButtonHandler = (enteredSubnet) => {
        this.getApiSubnetInfo(enteredSubnet);
        this.getApiSmallerSubnets(enteredSubnet);
    }


    render() {
        return (
            <main>
                <div className="container col-xl-10 col-xxl-8 px-4 py-5">
                    <div className="row align-items-center g-lg-5 py-5">
                        <h1 className="display-4 fw-bold lh-1 mb-3">IPv4 address calculator</h1>
                        <SubnetTableV4
                            outputTableData={this.state.outputTableData}
                        />
                        <SearchForm
                            placeholderForm="10.0.0.0/24"
                            labelForm="IPv4 subnet"
                            formValue={this.state.enteredSubnet}
                            onSubmittedSubnetForm={this.submittedSubnetFormHandler}
                        />
                    </div>
                    <SmallerSubnetsV4
                        onClickHandlerButton={this.smallerSubnetClickButtonHandler}
                        smallerSubnetsData={this.state.smallerSubnet}
                    />
                </div>
            </main>
        );

    }
}

export default Ipv4Layout;