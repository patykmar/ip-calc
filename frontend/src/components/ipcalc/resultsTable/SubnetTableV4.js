import React from "react";
import SubnetTableRow from "./SubnetTableRow";
import {Table} from "react-bootstrap";

class SubnetTableV4 extends React.Component {

    render = () => {
        return (
            <div className="col-lg-7 text-center text-lg-start">
                <Table striped bordered hover>
                    <tbody>
                    {Object.values(this.props.outputTableData).map((item, index) => (
                        <SubnetTableRow key={index} rowKey={item.key} rowValue={item.value}/>
                    ))}
                    </tbody>
                </Table>
            </div>
        );
    };
}

export default SubnetTableV4;