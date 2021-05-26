import React from "react";
import {Button, Col, Form} from "react-bootstrap";

export default class SearchForm extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            subnet: '',
        }
    }

    /**
     * Calling this function after submit inputForm
     * @param {*} event
     */
    submitFormHandle = (event) => {
        event.preventDefault();
        this.props.onSubmittedSubnetForm(this.state.subnet);
    };

    /**
     * Function which is handling onChange
     * @param {*} event
     */
    subnetChangeHandler = (event) => {
        this.setState({subnet: event.target.value});
    };

    render() {
        return (
            // <Form inline={true} onSubmit={this.submitFormHandle}>
            <Form onSubmit={this.submitFormHandle}>
                <Form.Row>
                    <Col xs={7}>
                        <Form.Label htmlFor="inlineFormInput" srOnly>
                            {this.props.labelForm}
                        </Form.Label>
                        <Form.Control
                            className="mb-2"
                            id="inlineFormInput"
                            placeholder={this.props.placeholderForm}
                            value={this.state.subnet}
                            onChange={this.subnetChangeHandler}
                        />
                        <Form.Text className="text-muted">
                            By clicking Calculate, application recalculate your subnet.
                        </Form.Text>
                    </Col>
                    <Col xs={2}>
                        <Button variant="primary" type="submit">Calculate</Button>
                    </Col>
                </Form.Row>
            </Form>
        );
    }
}
