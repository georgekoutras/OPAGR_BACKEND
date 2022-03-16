

import React, { useRef, useState, useEffect } from 'react';

const LoginFormContainer = () => {

    const [emailError, setEmailError] = useState('');
    const [passwordError, setPasswordError] = useState('');


    const emailInputRef = useRef();
    const passwordInputRef = useRef();

    const onSubmitHandler = async (event) => {
        event.preventDefault();

        if (!emailInputRef.current.value.includes('@') || passwordInputRef.current.value.trim() === '') {

            if (!emailInputRef.current.value.includes('@')) {
                setEmailError('Μη έγγυρο email');
            } else {
                setEmailError('');
            }

            if (passwordInputRef.current.value.trim() === '') {
                setPasswordError('Συμπληρώστε τον κωδικό');
            } else {
                setPasswordError('');
            }

            return;
        }

        console.log(emailInputRef.current.value);
        console.log(passwordInputRef.current.value);

        try {
            const response = await fetch('http://openagros.test/login', {
                method: 'POST',
                body: {email: emailInputRef.current.value, password: passwordInputRef.current.value},
                // headers: {
                //     'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                // }
                });

            passwordInputRef.current.value='';
            emailInputRef.current.value = '';
            setEmailError('');
            setPasswordError('');

            if(!response.ok) {
                throw new Error('Something went wrong...');
            }
        }
        catch(error) {
            //setError(error.message);
        }
    }

    return (
        <div className="col-md-4">
            <h4 className="pb-4">Είσοδος</h4>
            <form className="w-75" onSubmit={onSubmitHandler}>
                <div className="form-group pb-3">
                    <label htmlFor="email">Email</label>
                    <input 
                        type="text" 
                        className = "form-control" 
                        name="email" id="email" 
                        ref={emailInputRef}
                    />
                    {emailError && <span className="text-danger">{emailError}</span>}
                </div>
                <div className="form-group pb-3">
                    <label htmlFor="password">Κωδικός πρόσβασης</label>
                    <input 
                        type="password"
                        className = "form-control"
                        name="password" id="password"
                        ref={passwordInputRef}
                    />
                    {passwordError && <span className="text-danger">{passwordError}</span>}
                </div>

                <button type="submit" className="btn btn-success btn-block">Είσοδος</button>
            </form>
        </div>
    );
}

export default LoginFormContainer;