#include <ptime.h>
#include <pinet.h>

USING_PTYPES


#include <iostream>
#include <cstdio>
#include <cstring>
#include <cstdlib>
#include <sstream>


const int testport = 8085;
const int maxtoken = 4096;

void servermain(ipstmserver& svr)
{
    ipstream client;

    pout.putf("Ready to answer queries on port %d\n", testport);

    while(true)
    {
        // serve() will wait for a connection request and will prepare
        // the supplied ipstream object for talking to the peer.
        // note that (unlikely) exceptions thrown in serve() will be 
        // caught in main()
        svr.serve(client);
        
        // for better performance the server would start a new thread
        // for each client request. for simplicity, we serve the request
        // in-place. see multithreading examples for the full multithreaded
        // server template.
        if (client.get_active())
        {
            try
            {
                // read the request line:
                // real-world network applications should limit input data
                // to prevent potential denial-of-service attacks
                string req = lowercase(client.line(maxtoken));

                std::stringstream ss;
                std::stringstream primero;
                std::stringstream segundo;
                ss<<(const char*)(req);
                std::cout<<"data recibida: "<< ss.str()<<std::endl;

                char * data=new char[strlen(ss.str().c_str())+1];
                strcpy(data,ss.str().c_str());
                bool flag=false;
                for(int i=0;i<strlen(data);i++){
                    if(data[i]=='+'){
                        flag=true;
                    }
                    if(flag==false){
                        primero<<data[i];
                    }else{
                        segundo<<data[i];
                    }
                }
                primero<<std::endl;
                segundo<<std::endl;

                int p=0;
                int s=0;

                primero>>p;
                segundo>>s;

                int suma=0;
                suma = (int)p+(int)s;

                std::stringstream salida;
                salida<<suma;
                
                //if (req == "hello")
                //{
                    // try to reverse-lookup the client's IP
                    string host = phostbyaddr(client.get_ip());
                    if (isempty(host))
                        host = iptostring(client.get_ip());
                    
                    // now send our greeting to the client
                    client.putline((char *) salida.str().c_str());
                    client.flush();

                    // log this request
                    pout.putf("%t  greeting received from %s (%a)\n",
                        now(), pconst(host), long(client.get_ip()));
                //}

                client.close();
            }
            catch(estream* e)
            {
                perr.putf("Error: %s\n", pconst(e->get_message()));
                delete e;
            }
        }
    }
}

int main()
{
    ipstmserver svr;

    try
    {
        // bind to all local addresses on port 8085
        svr.bindall(testport);

        // enter an infinite loop of serving requests
        servermain(svr);
    }
    catch(estream* e)
    {
        perr.putf("FATAL: %s\n", pconst(e->get_message()));
        delete e;
    }

    return 0;
}