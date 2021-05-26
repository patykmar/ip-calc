import React from "react";
import {Button, Col, Row} from "react-bootstrap";

class SmallerSubnetsV4 extends React.Component {

    smallSubnetButtonClickHandler = (event) => {
        this.props.onClickHandlerButton(event.target.value);
    }

    render = () => {
        return (
                <Row>
                    {Object.values(this.props.smallerSubnetsData).map((smallerSubnet, index) => (
                        <Col key={Math.pow(index, 2).toString()}>
                            <ol key={Math.pow(index, 3).toString()}>
                                {Object.values(smallerSubnet).map((subnet, indexx) => (
                                    <li key={indexx}>
                                        <Button
                                            onClick={this.smallSubnetButtonClickHandler}
                                            key={subnet.subnet}
                                            variant="link"
                                            value={subnet.subnet}
                                        >{subnet.subnet}</Button>
                                    </li>
                                ))}
                            </ol>
                        </Col>
                    ))}
                </Row>
        );
    }
}

export default SmallerSubnetsV4;