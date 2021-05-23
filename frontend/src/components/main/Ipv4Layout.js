import LayoutOtput from "../layout/LayoutOutput";
import SearchForm from "../form/SearchForm";
import React from "react";
import axios from "axios";


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


class Ipv4Layout extends React.Component {

    constructor(props) {
        super(props);
        // must be initial
        this.state = {
            enteredSubnet: "",
            outputTableData: {},
        };
    }

    componentDidMount() {
        this.setState({
            enteredSubnet: "192.168.1.0/24",
            outputTableData: OUTPUT_TABLE_DATA_V4
        });
    }

    getApiData = (enteredSubnet = "172.20.30.0/24") => {
        axios
            .get(`http://localhost:8000/api/ipv4calc/${enteredSubnet}`)
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


    /**
     * Here I have output from web-form
     * @param {*} enteredSubnet
     */
    submittedSubnetFormHandler = (enteredSubnet) => {
        if (enteredSubnet.trim().length < 4) {
            return;
        }
        console.log("Logging from App.js - submittedSubnetFormHandler");
        console.log(enteredSubnet);
        this.getApiData(enteredSubnet);
    };


    render() {
        return (
            <main>
                <div className="container col-xl-10 col-xxl-8 px-4 py-5">
                    <div className="row align-items-center g-lg-5 py-5">
                        <h1 className="display-4 fw-bold lh-1 mb-3">IPv4 address calculator</h1>
                        <LayoutOtput
                            outputTableData={this.state.outputTableData}
                        />
                        <SearchForm
                            placeholderForm="10.0.0.0/24"
                            labelForm="IPv4 subnet"
                            onSubmittedSubnetForm={this.submittedSubnetFormHandler}
                        />
                    </div>
                </div>
            </main>
        );

    }
}

export default Ipv4Layout;