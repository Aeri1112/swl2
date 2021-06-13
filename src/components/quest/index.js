import React, { useEffect, useState } from 'react';
import { GET } from '../../tools/fetch';
import Stepper from '@material-ui/core/Stepper';
import Step from '@material-ui/core/Step';
import {Nav, Spinner} from "react-bootstrap";
import { LinkContainer } from 'react-router-bootstrap';
import DoneAllIcon from '@material-ui/icons/DoneAll';
import { StepButton } from '@material-ui/core';
import MobileStepper from '@material-ui/core/MobileStepper';
import Button from '@material-ui/core/Button';

const Quest = () => {

    const [loading, setLoading] = useState();
    const [data, setData] = useState();

    //quest-step
    const [step, setStep] = useState();
    const [stepData, setStepData] = useState();
    const [loadingStep, setLoadingStep] = useState();

    //ui-step
    const [activeStep, setActiveStep] = useState(0);

    const [width, setWidth] = useState(window.innerWidth);
    const breakpoint = 500;

    const loadQuests = async () => {
        setLoading(true);
        const response = await GET("/quest");
        if(response) {
            setData(response);
        }
        setLoading(false);
    }

    const loadQuestSteps = async () => {
        setLoadingStep(true);
        const response = await GET(`/quest/quest/${step}`);
        if(response) {
            setStepData(response);
            const step = +response.user_step.active_step - 1 || 0;
            setActiveStep(step)
        }
        setLoadingStep(false);
    }

    const handleQuestClick = (id) => {
        setStep(id)
    }

    const handleQuestStepClick = (id) => {
        if(id <= +stepData.user_step.active_step - 1) {
            setActiveStep(id);
        }
    }

    const handleNext = () => {
        setActiveStep(activeStep + 1);
    }

    const handleBack = () => {
        setActiveStep(activeStep - 1);
    }

    useEffect(() => {
        const handleResizeWindow = () => setWidth(window.innerWidth);
         // subscribe to window resize event "onComponentDidMount"
         window.addEventListener("resize", handleResizeWindow);
         return () => {
           // unsubscribe "onComponentDestroy"
           window.removeEventListener("resize", handleResizeWindow);
         };
    }, []);

    useEffect(() => {
        loadQuests();
    },[])

    useEffect(() => {
        if(step) {
            loadQuestSteps();
        }
    },[step])

    return ( 
        <div>
            {
                loading === false &&
                <div>
                    <Nav className="flex-column">
                        {data.quests.map((quest) => (
                            <LinkContainer key={quest.quest_id} to={`/quest/${quest.quest_id}`}>
                                <Nav.Link onClick={() => handleQuestClick(quest.quest_id)}>
                                    {
                                        quest.name + "  "
                                    }
                                    {
                                        data.user_quests[quest.quest_id].status === "done" &&
                                        <DoneAllIcon color="primary" />
                                    }
                                </Nav.Link>
                            </LinkContainer>
                        ))}
                    </Nav>                    
                </div>
            }
            {
                loadingStep === false &&
                <div>
                    <div className="text-center">{stepData.quest.name}</div>
                    <div className="text-center">{stepData.text}</div>
                    {
                        width > breakpoint ?
                            <Stepper activeStep={activeStep} alternativeLabel>
                                {stepData.quest_steps.map((step) => (
                                <Step key={step.step_id} onClick={() => handleQuestStepClick(step.step_id - 1)}>
                                    <StepButton completed={stepData.user_step[step.step_id].status === "done"}>
                                        {step.name}
                                    </StepButton>
                                </Step>
                                ))}
                            </Stepper>
                        :
                            <MobileStepper
                                variant="dots"
                                steps={stepData.quest_steps.length}
                                position="static"
                                activeStep={activeStep}
                                nextButton={
                                <Button size="small" onClick={handleNext} disabled={activeStep === (stepData.quest_steps.length - 1)}>
                                    Next
                                </Button>
                                }
                                backButton={
                                <Button size="small" onClick={handleBack} disabled={activeStep === 0}>
                                    Back
                                </Button>
                                }
                            />
                    }
                    {
                        <div className="text-center">
                            {
                                stepData.user_step[activeStep+1].status === "done" ?
                                    <div dangerouslySetInnerHTML={{ __html: stepData.quest_steps[activeStep].erledigttext }} />
                                :   stepData.quest_steps[activeStep].einleitungstext
                            }
                        </div>
                    }
                </div>
            }
            {
                loadingStep === true &&
                <div className="text-center">
                    <Spinner animation="border" />
                </div>
            }
        </div>
     );
}
 
export default Quest;