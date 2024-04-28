from fastapi import FastAPI,Form,Body
from starlette.requests import Request
from starlette.responses import HTMLResponse, PlainTextResponse
import uvicorn
import re
from pydantic import HttpUrl
from fastapi.templating import Jinja2Templates
import json
import requests
# app = FastAPI(openapi_url='') 

app = FastAPI() 





def check_host(url):
    with open('host/host.json', 'r') as token:
        regex = json.loads(token.read())
    for host in regex:
        for regx in regex[host]:      
            linkinfo = re.search(regx,url)
            if linkinfo is not None:
                
                try :
                    if linkinfo[2] !='':
                        return {"chapid":linkinfo[2],
                                            "bookid":linkinfo[1],
                                            "host":host}
                    else:
                        return {"chapid": None,
                                        "bookid":linkinfo[1],
                                        "host":host}
                    
                except :
                    return {"chapid": None,
                                        "bookid":linkinfo[1],
                                        "host":host}
    return  None   

def get_info(url):

    check_test =  check_host(url)
    if check_test is None:
        return None
    else:
        with open('host/'+check_test['host']+'.json', 'r', encoding='utf-8') as token:
            host = json.loads(token.read())
       
        if check_test['chapid'] is None:
            if  not host['utf-8']:
                datahost =  html_get(host['link_book_info'].format(id=check_test['bookid']),True)
            else:
                datahost =  html_get(host['link_book_info'].format(id=check_test['bookid']))
            content = re.search(host['reg_book'],datahost,re.S)
            if content is None:
                return None
            else:
            
                chaplist1 = get_list(check_test['bookid'],host,datahost)
                if chaplist1 is not None:
                    tag = None
                    img = content['thumb']
                    if host['tag'] is not None:
                        tag = re.findall(host['tag'] ,datahost,re.S)
                    if host['repimg'] is not None:
                        img = host['repimg']+content['thumb']
                    
                        
                    bookinfo = {
                        'name':content['bookname'],
                        'tacgia':content['author'],
                        'img':img,
                        'theloai':content['category'],
                        'gioithieu':content['description'],
                        'tag': tag,
                        'hostvip':host['vip'],
                        'bookid':check_test['bookid'],
                        'host':check_test['host'],
                        'linkorigin':host['link_book_info'].format(id=check_test['bookid']),
                        'utf-8':host['utf-8'],
                        'listchap':chaplist1
                    }
                    return bookinfo
                else:
                    return None
        else:
    
            if  not host['utf-8']:
                dataread =  html_get(host['link_chap_read'].format(id=check_test['bookid'],c=check_test['chapid']),True)
            else:
                dataread = html_get(host['link_chap_read'].format(id=check_test['bookid'],c=check_test['chapid']))
            print (dataread)
            chapread = re.search(host['reg_chap_read'],dataread,re.S)
            if chapread is not None:
                contentchap=chapread['content']
                if check_test['host']=='tadu':
                    contentchap = html_get(chapread['content']).replace("callback({content:'",'').replace("'})","")
                readreturn ={
                    'bookid':check_test['bookid'],
                    'chapid':check_test['chapid'],
                    'bookname':chapread['bookname'],
                    'name':chapread['chaptername'],
                    'content':contentchap
                }
                return readreturn
            else:
                return None
                    
        

def get_list(bookid,hostinfo,html):
    if hostinfo['reload']:
        datachap =  html_get(hostinfo['link_chap_list'].format(id=bookid))
    else:
        datachap = html
    chaplist = re.finditer(hostinfo['reg_chap_list'],datachap,re.S)
    
 
    if chaplist is not None:
        if hostinfo['sort']:
            chaplist = list(chaplist)
            chaplist.reverse()
        listchap = []
        for chap in chaplist:
            if hostinfo['repread'] is not None:
                link = hostinfo['repread'].format(id=bookid,c=chap['cid'] )
            else:
                link = hostinfo['link_chap_read'].format(id=bookid,c=chap['cid'] )
            try:
                if hostinfo['vip']:               
                    if chap['vip'] is None:
                        listchap.append({"id":chap['cid'] ,'vip':False,"namechap":chap['title'],"linkchap":link})       
                else:
                    listchap.append({"id":chap['cid'],'vip':False,"namechap":chap['title'],"linkchap":link})                      
            except:
        
                listchap.append({"id":chap['cid'],'vip':False,"namechap":chap['title'],"linkchap":link})
        
        return listchap
    else:
        return None
    
def html_get(url,checkutf=None):
    header = {
        'User-Agent':'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:94.0) Gecko/20100101 Firefox/94.0'
        
    }
    if checkutf is None:
        response = requests.get(url,headers=header,timeout=30)
    else:
        response = requests.get(url,headers=header,timeout=30)
        response.encoding = 'gb2312'
    return  response.text
    
    


@app.get('/getlink')
def get_link(link:HttpUrl):
    bookinfo = get_info(link)
    if bookinfo is None:
        return None
    else:
        return bookinfo
if __name__ == "__main__":
    uvicorn.run("main:app", host="0.0.0.0", port=7999, reload=True, server_header=False)
