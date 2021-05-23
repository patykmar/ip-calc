import React from "react";
import SubnetTableRow from "./SubnetTableRow";

class SubnetTableV4 extends React.Component {

    render = () => {
        console.log("SubnetTableV4 - render");
        console.log(this.props.outputTableData);
        console.log(this.props.outputTableData['broadcast-address']);
        console.log(this.props.outputTableData['broadcast-address']);
        return (
            <div className="col-lg-7 text-center text-lg-start">
                <table className="table">
                    <tbody>
                    {Object.values(this.props.outputTableData).map((item, index) => (
                        <SubnetTableRow key={index} rowKey={item.key} rowValue={item.value}/>
                    ))}
                    </tbody>
                </table>
            </div>
        );
    };
}

export default SubnetTableV4;