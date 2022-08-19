<h1 align="center">PHẦN MỀM QUẢN LÝ KÍ TÚC XÁ</h1>

## Đóng góp: 
- [Phạm Trí Hùng](https://github.com/Kuroo-nekoo)
- [Ngô Chí Cường](https://github.com/OhJiKang)
- [Phạm Minh Trí](https://github.com/tripm1405)
- [Đặng Thị Minh Thư](https://github.com/alexid-id)
- [Bùi Hữu Lộc](https://github.com/maoleng)

## Mục lục
- [1. Lời mở đầu](#1-Loi-mo-dau)
- [2. Giới thiệu](#2-Gioi-thieu)
- [3. Phân tích yêu cầu người dùng](#3-Phan-tich-yeu-cau-nguoi-dung)
- [4. Thiết kế hệ thống](#4-Phan-tich-va-demo-chuc-nang)
- [5. Xem trực tiếp sản phẩm](#5-Xem-truc-tiep-san-pham)

## 1 Loi mo dau
- Hiện nay, đất nước ta đang trong giai đoạn phát triển, thực hiện công nghiệp hóa, hiện đại hóa. Cùng với sự phát triển của nền kinh tế thị trường, việc xây dựng một phần mềm để quản lý quy trình làm việc một nhu cầu thiết yếu của người dân, các cơ quan xí nghiệp, các tổ chức kinh tế và toan xã hội.
- Với kiến thức học được trên trường, tự học, các anh chị truyền đạt kinh nghiệm, nhóm em đã học hỏi rất nhiều điều bổ ích từ kiến thức trong ngành cho đến đời sống. Đề tài đồ án của nhôm em là: `Phần mềm quản lý kí túc xá`
- Em xin chân thanh cảm ơn các thầy cô đã tạo cơ hội cho nhóm em thực hiện sản phẩm này
<p align="right">Hồ Chí Minh, tháng 8 năm 2022</p>

## 2 Gioi thieu

### 2.1 Đưa ra vấn đề
- Do hiện nay ở trường mình, em nhận thấy hệ thống quản lý kí túc xá trường mình chưa được phát triển mạnh lắm, còn gây khó khăn nhiều cho người sử dụng, ... Nhận thấy được sự bất tiện nên nhóm em đã xây dựng dự án này.
- Một phần vì nhóm em cũng muốn thử sức làm 1 phần mềm tương đối lớn, nhân dịp `Khoa CNTT` mở hội thi nên nhóm đã chọn dự án này.

### 2.2 Hệ thống hiện tại
- Sinh viên trường TDT khi ở kí túc xá phải tuân thủ luật lệ, vệ sinh, nề nếp. Nếu không thì sẽ bị `thầy tự quản` bắt lỗi vi phạm. Nếu 1 năm bị bắt lỗi vi phạm quá `4 lần` thì học kì tới sẽ `không được kí hợp đồng`. Vấn đề nằm ở chỗ khi các `thầy tự quản` đi bắt lỗi, các thầy phải thực hiện khá nhiều thao tác:
  - Gọi 1 bạn `sinh viên tự quản` đi theo để cầm hộ thầy cuốn sổ
  - Khi muốn `ghi lỗi` thì phải ghi vào sổ sách
  - Dùng điện thoại cá nhân chụp minh chứng vi phạm của sinh viên, lưu vào điện thoại
  - Sau khi ghi tên, nội dung lỗi vi phạm xong thì phải đưa cho học sinh ấy `kí xác nhận lỗi`
- Sinh viên khi muốn xác nhận lỗi
  - Nếu hôm ấy sinh viên không có ở phòng mà bị dính lỗi thì 10 rưỡi đêm phải xuống tầng trệt để kí lỗi
- Sinh viên xin phép vắng, có ý kiến gì đó cần nộp đơn
  - Hệ thống nộp đơn hiện tại hơi khó dùng, khó chèn ảnh vào đơn
  - Phải liên lạc với anh tự quản cùng tầng
- Sinh viên tự quản khi điểm danh mỗi buổi tối phải kiểm tra theo số giường, không thể kiểm tra theo tên sinh viên
- Sinh viên tự quản đăng kí lịch gác cổng ra vào qua giấy tờ


### 2.3 Hệ thống đề nghị
- Cần có hệ thống mới, tiên tiến, hiện đại hơn, có những chức năng cơ bản để quản lí kí túc xá như quản lý phòng, hợp đồng, hóa đơn điện nước, phòng và sinh viên, các bài đăng, điểm danh của sinh viên, ...
- Lưu được các vi phạm, ảnh minh chứng lên hệ thống, quản lý các vi phạm dễ dàng
- Sinh viên có thể xác nhận lỗi trên hệ thống, xem các vi phạm của mình, sẽ không còn `cuốn sổ`
- Nộp đơn, xin phép, liên lạc giáo viên dễ dàng hơn
- Điểm danh sinh viên mỗi tối nhanh hơn
- Đăng kí lịch trực gác cổng ra vào mỗi tuần trên hệ thống

## 3 Phan tich yeu cau nguoi dung

### 3.1 Yêu cầu phi chức năng
- Dễ hiểu, giao diện đẹp, đầy đủ tinh năng, dùng được trên trinh duyệt

### 3.2 Yêu cầu chức năng

#### 3.2.1 Nhóm người dùng của hệ thống

##### Giáo viên tự quản
- Đăng nhập, đăng xuất
-	Quản lí các vi phạm (xem, thêm, chỉnh sửa)
-	Quản lí hóa đơn ( trả lời đơn của học sinh )

##### Giáo viên quản lý
- Có các chức năng của giáo viên tự quản
- Quản lý các đơn đăng kí xin vào kí túc xá
- Quản lý hơp đồng
- Quản lý phòng và sinh viên
- Quản lý hóa đơn điện nước
- Quản lý bài đăng
- Quản lý điểm danh sinh viên mỗi tối

##### Sinh viên không nằm trong kí túc xá
- Đăng nhập, đăng xuất
- Đăng kí ở nội trú

##### Sinh viên trong kí túc xá
- Đăng nhập, đăng xuất
- Xem hợp đồng
- Xem các vi phạm của bản thân, xác nhận lỗi

##### Sinh viên tự quản
- Có các chức năng của sinh viên trong kí túc xá
- Điểm danh sinh viên mỗi tối
- Đăng kí lịch trực gác cổng mỗi tuần

## 4 Phan tich va demo chuc nang

### 4.1 Sơ đồ quan hệ thực thể
![image](https://user-images.githubusercontent.com/91431461/184504835-f1c9b8fc-e9a0-4f67-a0d8-a692bf1d4b50.png)

### 4.2 Sơ đồ cơ sở dữ liệu
- Chức năng đăng bài
![image](https://user-images.githubusercontent.com/91431461/184504449-577cd871-1891-4a3e-94f4-e57aff2ced4e.png)
- Chức năng đăng nhập
![image](https://user-images.githubusercontent.com/91431461/184504472-81b24a10-a0ed-4d98-87b0-c6d4d0b6d078.png)
- Giáo viên ghi lỗi sinh viên
![image](https://user-images.githubusercontent.com/91431461/184504497-70deb0b9-16ae-4c10-b968-8ea16149169f.png)
- Sinh viên đăng kí vào kí túc xá, được duyệt, chọn phòng, và trả tiền
![image](https://user-images.githubusercontent.com/91431461/184504575-734edaba-f0e8-4bd1-8742-522beee1d6ad.png)
- Quản lý tiền điện nước
![image](https://user-images.githubusercontent.com/91431461/184504594-9d375ff7-68cd-4eb7-9301-fcc111482efc.png)
- Điểm danh sinh viên mỗi tối
![image](https://user-images.githubusercontent.com/91431461/184504665-31a8f16c-621c-4608-b374-d1213d9f7a90.png)
- Sinh viên đăng kí lịch trực gác cổng ra vào
![image](https://user-images.githubusercontent.com/91431461/184504697-4ede3b65-b20b-4f4b-bf3e-3d55fdaee79e.png)
- Sinh viên nộp đơn và giáo viên trả lời
![image](https://user-images.githubusercontent.com/91431461/184504765-ed8a3a4e-1817-4c62-840e-0b68281cfa5b.png)
- Quản lý phòng và sinh viên
![image](https://user-images.githubusercontent.com/91431461/184504827-6d05f97f-f561-432e-9cc2-aaab5140b8a6.png)

## 4 Phân tích và demo chức năng

## 5 Xem truc tiep san pham
[dormitory.fun](https://dormitory.fun)
