import React from "react";

// import Ipv4Layout from "./components/ipcalc/Ipv4Layout";
import Ipv6Layout from "./components/ipcalc/Ipv6Layout";



export default class App extends React.Component {

    render() {
        return (
            <div>
                <Ipv6Layout />
            </div>
    );
    }
}
