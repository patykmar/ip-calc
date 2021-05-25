import React from "react";
import SubnetTableRow from "./SubnetTableRow";

export default class SubnetTableV4 extends React.Component {

    render = () => {
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
