from fastapi import FastAPI,Form,Body
from starlette.requests import Request
from starlette.responses import HTMLResponse, PlainTextResponse
import uvicorn
import re
from fastapi.templating import Jinja2Templates
from pydantic import HttpUrl
import json
import requests,hashlib
import os
import os.path
app = FastAPI(openapi_url='') 
template = Jinja2Templates(directory='html')


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
                    'host':check_test['host'],
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
        response.encoding = 'GB18030'
    return  response.text


    
@app.get('/listfl',response_class=HTMLResponse)
def getfaloo(bookid):
    tor = dict(http='socks5://127.0.0.1:9050',
                https='socks5://127.0.0.1:9050')
    header = {
        "User-Agent":"Mozilla/5.0 (Windows NT 10.0; rv:91.0) Gecko/20100101 Firefox/91.0"
              }
    sign = hashlib.md5(("tengfeikk&"+str(bookid)+"&20191011#tengfeikk#111").encode('utf-8')).hexdigest()
    try:
        data = requests.get(f"http://open.feilu.cc/MiGu/getChapterList/?appid=tengfeikk&bookId={bookid}&sign={sign}",headers=header,timeout=30).text
        if "本书没有授权" in data:
            return {"status":"error","code":1,"data":"Truyện Không Hỗ Trợ"}
        data = json.loads(data)
        datachap = data['data']
        datachap.sort(key=lambda d: (int(d['volumeId']), int(d['sortNo'])))
    except:
        return {"status":"error","code":2,"data":"không lấy được danh sách chương"}
    text = ''
    if os.path.exists(str(bookid)) :
        pass
    else:
        os.mkdir(str(bookid))
    text =''
    for chapid in datachap:
        text += f"{bookid}-{chapid['id']}-{chapid['name']}<br>"
        
    return text

@app.get('/chapfaloo/{bookid}/{chapid}',response_class=HTMLResponse)
def getchapfaloo(bookid, chapid):
    bookid = str(bookid)
    chapid = str(chapid)
    tor = dict(http='socks5://127.0.0.1:9050',
                https='socks5://127.0.0.1:9050')
    header = {
        "User-Agent":"Mozilla/5.0 (Windows NT 10.0; rv:91.0) Gecko/20100101 Firefox/91.0"
              }
    if os.path.exists(bookid) :
        pass
    else:
        os.mkdir(bookid)
    if os.path.exists(f"{bookid}/{chapid}.txt"):
        datatext = ''
        with open(f"{bookid}/{chapid}.txt", 'r', encoding='utf-8') as f:
            datatext = f.read()
            return datatext.replace("\n","<br>")
    sign2 = hashlib.md5((f"tengfeikk&{bookid}&{chapid}&20191011#tengfeikk#111").encode('utf-8')).hexdigest()
    try:
        datachapter3 = requests.get(f"http://open.feilu.cc/MiGu/getChapterInfo/?appid=tengfeikk&bookId={bookid}&chapterid={chapid}&sign={sign2}",headers=header,timeout=20).text
        datachapter = json.loads(datachapter3)
    except:
        return {"status":"error","code":2,"data":"Không tải được web faloo"}
    if "本书没有授权" in datachapter:
        return {"status":"error","code":1,"data":"Truyện Không Hỗ Trợ"}
    if datachapter['data']['content'] != "null":
        datatext =  datachapter['data']['name']+"\r\n"+datachapter['data']['content'].replace("\u3000","")+"\r\n<$$$===$$$>\r\n"
        with open(f"{bookid}\{chapid}.txt", 'w', encoding='utf-8') as f5:
                f5.write(datatext)
        return datatext.replace("\r\n","<br><br>")
    else:
        return {"status":"error","code":3,"data":"Chương không có nội dung"}




@app.get('/getlink')
def get_link(link:HttpUrl):
    bookinfo = get_info(link)
    if bookinfo is None:
        return None
    else:
        return bookinfo
    
@app.get('/gethost')
def get_link(link:HttpUrl):
    hostinfo = check_host(link)
    if hostinfo is None:
        return None
    else:
        return hostinfo
    
       
@app.post("/upload/")
async def up_load_mul(listtext =Form(...),orders:int = Form(...)):
    list_chuong=[]
    try:
        cattext = re.split("<\$\$\$===\$\$\$>",listtext)
        print(len(cattext))
        if len(cattext) <= 1 :
           return "error"
        for text in cattext:
            findtext = text.strip().split('\n')
            if len(findtext) <=1  :
                continue
            cattext3 = text.replace(findtext[0],'')
            cattext3 = cattext3.strip()
            print(findtext[0])
            cattext3 = cattext3.replace('\r\n\r\n\r\n\r\n',"<br><br>").replace('\r\n\r\n\r\n',"<br><br>").replace('\r\n\r\n',"<br><br>").replace('\r\n',"<br><br>")
            list_chuong.append({"title":findtext[0],"content":cattext3,"orders":orders})
            orders += 1
    except:
        list_chuong= "error"
    return list_chuong

@app.get("/upload/",response_class=HTMLResponse)
async def up_load_form(req:Request):
    return template.TemplateResponse("upload.html",{"request":req})
@app.get("/")
async def indexs():
    return {"error":1}
if __name__ == "__main__":
    uvicorn.run("main:app", host="0.0.0.0", port=8000, reload=True, server_header=False)
