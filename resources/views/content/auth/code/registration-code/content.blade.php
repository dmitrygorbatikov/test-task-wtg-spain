@php
    $supportEmail = view('components.general.email', ['email' => config('app.support_email')]);
@endphp
<tr style="background-color: #fff">
    <td>
        <table
            border="0"
            cellpadding="0"
            cellspacing="0"
            style="
                          width: 100%;
                          font-size: 14px;
                          max-width: 500px;
                          padding: 0 24px 0;
                          border-radius: 4px;
                          box-sizing: border-box;
                          background-size: cover;
                          font-family: 'Arial', sans-serif;
                        "
        >
            <tbody>
            <tr>
                <td>
                    <p
                        style="
                                  margin: 0;
                                  color: #040b1c;
                                  font-size: 36px;
                                  font-weight: 700;
                                  line-height: 48px;
                                  padding: 0 0 40px;
                                  font-family: 'Arial', sans-serif;
                                "
                    >
                        {{ __('emails/auth/registration-code.title') }}
                    </p>
                    <p
                        style="
                                  margin: 0;
                                  color: #373b46;
                                  font-size: 16px;
                                  text-align: left;
                                  line-height: 30px;
                                  font-family: 'Arial', sans-serif;
                                "
                    >
                        {{ __('emails/auth/registration-code.content.1') }}
                    </p>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 40px">
                    <div
                        style="
                                  background-color: #ecedf2;
                                  border-radius: 40px;
                                  display: inline-block;
                                  padding: 8px 16px;
                                  margin: 0 auto;
                                "
                    >
                        <p
                            style="
                                    display: inline-block;
                                    color: #151719;
                                    font-size: 24px;
                                    font-weight: 700;
                                    line-height: 36px;
                                    font-family: 'Arial', sans-serif;
                                    margin: 0;
                                  "
                        >
                            {{strtoupper($code)}}
                        </p>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 40px">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
                        <tr>
                            <td>
                                <p
                                    style="
                                          margin: 0;
                                          color: #373b46;
                                          font-size: 16px;
                                          max-width: 452px;
                                          line-height: 30px;
                                          font-family: 'Arial', sans-serif;
                                        "
                                >
                                    {!!
                                    __('emails/auth/registration-code.content.2',
                                    ['support_email' => $supportEmail]) !!}
                                </p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>
