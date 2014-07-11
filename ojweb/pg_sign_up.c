#include "oj.h"

int pg_sign_up()
{
    if (!http_request_method_is_GET())
        return 1;

    if (strcmp(http_request_path(), "/signup") != 0)
        return 1;

    print_header("Sign Up");
    print("<div class='row'>");
    print("<div class='large-12 columns'>");
    print("<form method='POST' action='/signup'>");

    print_text_field("username", "Username");
    print_password_field("password", "Password");
    print_password_field("confirm_password", "Confirm Password");
    print_text_field("name", "Name");

    print("<div class='row'>");
        print("<div class='large-6 columns'>");
            print("<label>Sex</label>");
            /* name, value, id, label */
            print_radio("sex", "male", "sex_male", "Male");
            print_radio("sex", "female", "sex_female", "Female");
        print("</div>");
    print("</div>");

    print_text_field("email", "Email");
    print_text_field("phone", "Phone");
    print_text_field("memo", "Memo");

    print("<input class='button expand' type='submit' value='Submit'>");

    print("</form>");
    print("</div>");
    print("</div>");
    print_footer();

    return 0;
}
