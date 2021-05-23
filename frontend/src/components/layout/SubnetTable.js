import React from "react";
import SubnetTableRow from "./SubnetTableRow";

export default class SubnetTable extends React.Component {

    render = () => {
        return (
            <table className="table">
                <tbody>
                {Object.values(this.props.outputTableData).map((item, index) => (
                    <SubnetTableRow key={index} rowKey={item.key} rowValue={item.value}/>
                ))}
                </tbody>
            </table>
        );
    };
}
