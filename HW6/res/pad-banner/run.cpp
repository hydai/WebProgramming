#include <cstdio>

int main(int argc, char *argv[])
{
    char url[] = "http://pad.skyozora.com/images/pets_pic/";
    char cmd[] = " -o ";
    for (int i = 1; i <= 1119; i++) {
        printf("curl %s%d.jpg %s %d.jpg\n", url, i, cmd, i);
    }
    return 0;
}
