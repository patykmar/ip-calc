import React from "react";
import Button from "./Button";

export default class SearchForm extends React.Component {
    constructor(props) {
        super(props);
        this.placeholderForm = props.placeholderForm;
        this.labelForm = props.labelForm;
        this.state = {
            subnet: props.placeholderForm,
        };
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
            <div className="col-md-10 mx-auto col-lg-5">
                <form
                    className="p-4 p-md-5 border rounded-3 bg-light"
                    onSubmit={this.submitFormHandle}
                >
                    <div className="form-floating mb-3">
                        <input
                            type="text"
                            className="form-control"
                            value={this.state.value}
                            name="subnet"
                            onChange={this.subnetChangeHandler}
                            id="floatingInput"
                            placeholder={this.placeholderForm}
                        />
                        <label htmlFor="floatingInput">{this.labelForm}</label>
                    </div>
                    <Button type={"submit"}>Calculate</Button>
                    <hr className="my-4"/>
                    <small className="text-muted">
                        By clicking Calculate, application recalculate your subnet.
                    </small>
                </form>
            </div>
        );
    }
}
