import React from "react";
import SubnetTable from "./SubnetTable";

export default class LayoutOutput extends React.Component {

    render() {
        return (
            <div className="col-lg-7 text-center text-lg-start">
                <SubnetTable
                    outputTableData={this.props.outputTableData}
                />
            </div>
        );
    }
}
