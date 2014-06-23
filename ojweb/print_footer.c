#include "oj.h"

void print_footer()
{
    print("<footer class='row'>");
        print("<div class='large-12 columns'>");
            print("<hr/>");
            print("<div class='row'>");
                print("<div class='large-6 columns'>");
                    print("<p>Copyright &copy; 2010-");
                    print_current_year();
                    print(" <a href='http://acm.sjtu.edu.cn'>ACM Class</a>. ");
                    print("All rights reserved.</p>");
                print("</div>");
                print("<div class='large-6 columns'>");
                    print("<ul class='inline-list right'>");
                    print("<li><a href='/admin'>Admin</a></li>");
                    print("</ul>");
                print("</div>");
            print("</div>");
        print("</div>");
    print("</footer>");
    print("<script src='/js/vendor/jquery.js'></script>");
    print("<script src='/js/foundation.min.js'></script>");
    print("<script>$(document).foundation();</script>");
    print("<script src='http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML'></script>");
    print("</body>");
    print("</html>");
}
