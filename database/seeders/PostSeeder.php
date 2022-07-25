<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::query()->create([
            'title' => 'Đăng ký nội trú',
            'category' => 5,
            'teacher_id' => Teacher::query()->inRandomOrder()->value('id'),
            'content' => "<p><strong>I. HƯỚNG DẪN TÂN SINH VIÊN ĐĂNG KÝ Ở NỘI TRÚ:</strong></p><p>- Đối tượng tiếp nhận hồ sơ đăng ký: Là tân sinh viên Đại học Tôn Đức Thắng, có hộ khẩu thường trú ở xa, không thuộc các quận huyện nội thành Thành phố Hồ Chí Minh <em>(Đối với sv có hộ khẩu tại HCM, KTX chỉ tiếp nhận hồ sơ sinh viên ở huyện Củ Chi và Cần Giờ)</em> có nguyện vọng đăng ký ở nội trú Ký túc xá (KTX).</p><p>- Thời gian, địa điểm tiếp nhận hồ sơ đăng ký: Vào thời điểm nộp hồ sơ nhập học đối với tân sinh viên. Sau khi hoàn tất thủ tục nhập học, sinh viên có nguyện vọng đăng ký KTX, liên hệ tại bàn đăng ký Ký túc xá, xuất trình biên lai thu tiền học phí để được hướng dẫn.</p><p>- Sinh viên chuẩn bị hồ sơ đăng ký bao gồm:</p><p>· Bản sao chứng thực chứng minh nhân dân/ thẻ căn cước</p><p>· Bản sao chứng thực sổ hộ khẩu</p><p>· Bản sao giấy báo nhập học</p><p>· Giấy tờ minh chứng diện ưu tiên chính sách nếu có (bản chính hoặc bản sao chứng thực)</p><p><strong>II. HƯỚNG DẪN ĐĂNG KÝ Ở NỘI TRÚ CHO SINH VIÊN NĂM 2, 3, 4, 5:</strong></p><p>- Đối tượng tiếp nhận hồ sơ đăng ký: Là sinh viên Đại học Tôn Đức Thắng còn trong chương trình đào tạo, có hộ khẩu thường trú ở xa, không thuộc các quận huyện nội thành Thành phố Hồ Chí Minh <em>(KTX tiếp nhận hồ sơ sinh viên huyện Củ Chi, Cần Giờ)</em> có nguyện vọng đăng ký ở nội trú Ký túc xá (KTX).</p><p>- Sinh viên chuẩn bị hồ sơ đăng ký bao gồm:</p><p>· Bản sao chứng thực chứng minh nhân dân/ thẻ căn cước</p><p>· Bản sao chứng thực sổ hộ khẩu</p><p>· Giấy tờ minh chứng diện ưu tiên chính sách nếu có (bản chính hoặc bản sao chứng thực)</p><p>· Phiếu đăng ký ở nội trú</p><p>- Nộp tại văn phòng I.005 Ký túc xá nhà I và chờ xét.</p><p>(Căn cứ vào tình hình thực tế khi nộp hồ sơ các thầy cô sẽ thông báo thời gian dự kiến xét tiếp nhận nội trú cho sinh viên biết)</p><p><strong><em>THỨ TỰ ƯU TIÊN XÉT TIẾP NHẬN SINH VIÊN VÀO Ở NỘI TRÚ TẠI KÝ TÚC XÁ</em></strong></p><p>1. Con liệt sĩ, con thương binh, bệnh binh <em>(Bản sao công chứng thẻ thương binh, bệnh binh hoặc giấy xác nhận của cơ quan chức năng có thẩm quyền).</em></p><p>2. Con đẻ của những người hoạt động kháng chiến bị nhiễm chất độc hoá học <em>(Giấy chứng nhận người hoạt động kháng chiến bị nhiễm chất độc hóa học của cơ quan chức năng có thẩm quyền).</em></p><p>3. Sinh viên là người dân tộc thiểu số.</p><p>4. Có hộ khẩu và sinh sống tại xã khó khăn thuộc Chương trình 135/CP, xã Bãi ngang ven biển.</p><p><em>5. </em>Sinh viên là người khuyết tật, sinh viên mồ côi cả cha lẫn mẹ không nơi nương tựa ( <em>có giấy chứng nhận khuyết tật, sinh viên sinh sống tại Làng SOS, hoặc có giấy tờ chứng tử của cả cha và mẹ…)</em></p><p>6. Sinh viên gia đình thuộc diện xoá đói giảm nghèo theo qui định của nhà nước (<em>gia đình có sổ hộ nghèo, sổ hộ cận nghèo</em>).</p><p>7. Sinh viên là Đảng viên, bộ đội, công an đã hoàn tất nghĩa vụ và xuất ngũ.</p><p>8. Sinh viên là con, là anh chị em ruột của Cán bộ công đoàn hiện là Ủy viên Ban chấp hành cấp trên cơ sở trở lên <em>(Quyết định công nhận Ủy viên Ban chấp hành công đoàn cấp trên cơ sở trở lên còn hiệu lực).</em></p><p>9. Sinh viên thuộc các trường hợp ngoại lệ khác, có minh chứng đính kèm.</p><p>10. Sinh viên khu vực tuyển sinh theo thứ tự:</p><ul><li>KV1 không thuộc thành phố, thị xã</li><li>KV2-NT không thuộc thành phố, thị xã</li><li>KV1 thuộc thành phố, thị xã</li><li>KV2-NT thuộc thành phố, thị xã</li><li>KV2</li><li>KV3</li></ul><p><a href='https://drive.google.com/file/d/1NabNYu6dTwa7MlFSUdHZD2tTUYG1jJ5Y/view?usp=sharing'>Mẫu phiếu đăng ký nội trú: Tại đây</a></p><p>.</p>",
        ]);
        Post::query()->create([
            'title' => 'Gửi xe, Đổi xe, Chấm dứt HĐ xe',
            'category' => 5,
            'teacher_id' => Teacher::query()->inRandomOrder()->value('id'),
            'content' => '<p><strong>1. HƯỚNG DẪN THỦ TỤC GỬI XE THÁNG</strong></p><p>Sinh viên nội trú (SVNT) có nguyện vọng đăng ký gửi xe tại Ký túc xá, hoàn tất mẫu <strong>đơn đăng ký gửi xe</strong> (gồm 02 tờ), chuẩn bị sẵn 02 ảnh 2x3 (lưu ý bắt buộc ảnh 2x3 và không dán ảnh vào đơn)</p><p>Sinh viên liên hệ văn phòng I.005 để đóng phí, phí gửi xe được thu từ thời điểm gửi xe đến hết học kỳ.</p><p>Đơn giá thu phí: xe máy: 80.000đ/tháng, xe đạp: 40.000đ/tháng</p><p>Sau khi đóng phí sinh viên mang hồ sơ đăng ký gửi xe có xác nhận của ký túc xá nộp tại phòng A.0306 (Phòng Thanh tra pháp chế và An ninh) để làm thẻ xe.</p><p>Sinh viên theo dõi thời hạn đóng phí gửi xe, thực hiện đúng nghĩa vụ đóng gia hạn theo thông báo của ký túc xá (trước kết thúc mỗi học kỳ). Các trường hợp gửi xe quá hạn sẽ bị xử lý đúng qui định.</p><p>Mẫu đơn gửi xe: <a href="https://drive.google.com/file/d/1JjYlhcALWlNIvtf8-56q3n7-pxatIa1I/view?usp=sharing">Tại đây</a></p><p><strong>2. HƯỚNG DẪN THỦ TỤC ĐỔI XE</strong></p><p>Sinh viên đang gửi xe tại tầng hầm Ký túc xá có nguyện vọng đổi sang xe khác hoàn tất<strong> mẫu đơn đổi xe </strong>liên hệ văn phòng I.005 để xác nhận.</p><p>Mang đơn đã được xác nhận nộp lên phòng A.0306 (Phòng Thanh tra pháp chế và An ninh) để cập nhật thông tin xe.</p><p>Mẫu đơn xin đổi xe: <a href="https://drive.google.com/file/d/118Wrms7fcWI2TsCMwjbK3xupKYMsQgiV/view?usp=sharing">Tại đây</a></p><p><strong>3. HƯỚNG DẪN THỦ TỤC CHẤM DỨT HỢP ĐỒNG GỬI XE</strong></p><p>Sinh viên đang gửi xe tháng tại tầng hầm Ký túc xá có nguyện vọng kết thúc gửi xe làm <strong>đơn xin chấm dứt hợp đồng</strong> gửi xe liên hệ phòng I005 để xác nhận.</p><p>Mẫu đơn xin CDHĐ xe: <a href="https://drive.google.com/file/d/1h8ZatMqq9ihCu_c20E_e-W6TF9IwgDtn/view?usp=sharing">Tại đây</a></p>',
        ]);
        Post::query()->create([
            'title' => 'Chấm dứt hợp đồng nội trú',
            'category' => 5,
            'teacher_id' => Teacher::query()->inRandomOrder()->value('id'),
            'content' => '<p>Sinh viên đang lưu trú ký túc xá có nguyện vọng kết thúc hợp đồng ở nội trú trước thời hạn phải nộp đơn xin chấm dứt hợp đồng về văn phòng KTX I005 trước từ 10 đến 15 ngày. Mỗi tháng có 02 đợt kết thúc hợp đồng vào ngày 15 và cuối mỗi tháng.</p><p>Khi chuyển ra Ký túc xá, sinh viên phải phải dọn dẹp vệ sinh góc học tập, phòng ở sạch sẽ và liên hệ bàn trực các tòa nhà để làm thủ tục bàn giao tài sản.</p><p>Mẫu đơn xin chấm dứt hợp đồng: <a href="https://docs.google.com/document/d/1s6clZViUOPcbmMyOh5HcTpuDywO1HNiB/edit?usp=sharing&amp;ouid=109304327871222195539&amp;rtpof=true&amp;sd=true">Tại đây</a></p>',
        ]);
        Post::query()->create([
            'title' => 'Xin về trễ sau giờ quy định (22h00)',
            'category' => 5,
            'teacher_id' => Teacher::query()->inRandomOrder()->value('id'),
            'content' => '<p>Sinh viên nội trú đi làm thêm, học thêm có nguyện vọng xin phép về trễ sau giờ quy định (sau 22h00) phải làm đơn xin về trễ mang đến cho đơn vị làm thêm, học thêm ký, đóng dấu mộc (nếu có) để xác nhận và số điện thoại của quản lý.</p><p>Mang đơn về nộp lại văn phòng I.005 hoặc văn phòng nhà K.</p><p>Viên chức tiếp nhận đơn sẽ thông báo thời gian nhận giấy phép về trễ cho sinh viên.</p><p>Lưu ý: Thời gian tối đa về đến KTX là 23h00.</p><p>Đơn xin về trễ: <a href="https://docs.google.com/document/d/1tu1n-SJ2zQubhdulYhCPiqxj8zM8ilaQ/edit?usp=sharing&amp;ouid=109304327871222195539&amp;rtpof=true&amp;sd=true">Tại đây</a></p>',
        ]);
        Post::query()->create([
            'title' => 'Tạm vắng - Tạm trú',
            'category' => 5,
            'teacher_id' => Teacher::query()->inRandomOrder()->value('id'),
            'content' => '<p style="text-align:start; text-indent:0px; -webkit-text-stroke-width:0px">Hướng dẫn về việc xin phép vắng mặt tại Ký túc xá:</p><p>- Sinh viên vắng mặt 01 đêm phải thông tin cho tổ trưởng phòng hoặc thành viên phòng ở để báo cáo cho sinh viên tự quản dãy lầu phụ trách điểm danh.</p><p>- Sịnh viên vắng mặt từ 02 đêm trở lên phải có đơn vắng mặt, ghi rõ thời gian vắng, lý do vắng, sau đó chuyển đơn đến sinh viên tự quản dãy lầu phụ trách điểm danh tổng hợp.</p><p>Đơn xin vắng mặt: <a href="https://docs.google.com/document/d/1Nx-b3WkjzRYVOMGPGS3pJJSrE36dOjsj/edit?usp=sharing&amp;ouid=109304327871222195539&amp;rtpof=true&amp;sd=true">Tại đây</a></p><p class="MsoNormal" style="text-align:justify"><span style="font-size:13.0pt;&#10;line-height:107%;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;"><p></p></span></p>'
        ]);
    }
}
