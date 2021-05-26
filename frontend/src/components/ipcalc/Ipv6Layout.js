import SearchForm from "./inputForm/SearchForm";
import React from "react";
import axios from "axios";
import SubnetTableV6 from "./resultsTable/SubnetTableV6";
import {Container, Row} from "react-bootstrap";
import TitleH1 from "./resultsTable/layout/TitleH1";


const API_URL = "http://localhost:8000/";

const OUTPUT_TABLE_DATA_V6 = {
    "lookup-address": {key: "Lookup address:", value: "abc::/64"},
    "network-subnet": {key: "Network subnet:", value: "abc:0:0:0:0:0:0:0/64"},
    "netmask": {key: "Netmask:", value: "ffff:ffff:ffff:ffff:0:0:0:0"},
    "network-address": {key: "Network address:", value: "abc:0:0:0:0:0:0:0"},
    "last-address": {key: "Last address:", value: "abc:0:0:0:ffff:ffff:ffff:ffff"}
}

class Ipv6Layout extends React.Component {

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
            enteredSubnet: "cde::/64",
            outputTableData: OUTPUT_TABLE_DATA_V6
        });
    }

    getApiData = (enteredSubnet = "bcd::/64") => {
        axios
            .get(`${API_URL}api/ipv6calc/${enteredSubnet}`)
            .then((res) => {
                this.setState({
                    enteredSubnet: enteredSubnet,
                    outputTableData: res.data,
                });
            })
            .catch((error) => {
                console.log("Logging Axios Error");
                console.log(error);
                this.setState({
                    enteredSubnet: enteredSubnet,
                    outputTableData: OUTPUT_TABLE_DATA_V6,
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
        this.getApiData(enteredSubnet);
    };


    render() {
        return (
            <main>
                <Container>
                    <TitleH1>IPv6 address calculator</TitleH1>
                    <Row>
                        <SearchForm
                            placeholderForm="abc::/64"
                            labelForm="IPv6 subnet"
                            onSubmittedSubnetForm={this.submittedSubnetFormHandler}
                        />
                    </Row>
                    <Row>
                        <SubnetTableV6
                            htmlH1="IPv6 address calculator"
                            outputTableData={this.state.outputTableData}
                        />

                    </Row>
                </Container>
            </main>
        );

    }
}

export default Ipv6Layout;